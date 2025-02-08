<?php

namespace App\Http\Controllers;

use App\DataTables\CorrespondanceDataTable;
use App\Http\Requests\CreateCorrespondanceRequest;
use App\Http\Requests\UpdateCorrespondanceRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\CorrespondanceRepository;
use App\Models\{Profil, Effectif, User, AnneeScolaire, Parents, Eleve, Classe, UserProfil};
use App\Mail\CorrespondanceMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Mail, Http};
//use Flash;
use Laracasts\Flash\Flash;

class CorrespondanceController extends AppBaseController
{
    /** @var CorrespondanceRepository $correspondanceRepository*/
    private $correspondanceRepository;

    public function __construct(CorrespondanceRepository $correspondanceRepo)
    {
        $this->correspondanceRepository = $correspondanceRepo;
    }

    /**
     * Display a listing of the Correspondance.
     */
    protected function getFormData()
    {
        return [
            'profils' => Profil::pluck('libelle', 'id')->toArray(),
            'classes' => Classe::pluck('libelle', 'id')->toArray(),
        ];
    }

    public function index(CorrespondanceDataTable $correspondanceDataTable)
    {
        return $correspondanceDataTable->render('correspondances.index', $this->getFormData());
    }


    /**
     * Show the form for creating a new Correspondance.
     */
    public function create()
    {
        $user = auth()->user();
        $userProfil = DB::table('user_profil')->where('user', $user->id)->first();

        $isEnseignant = false;
        $classes = [];
        $profils = Profil::query(); // Début de la requête

        if ($userProfil && isset($userProfil->personnel)) {
            $isEnseignant = true;
            // Filtrer les profils pour enseignants
            $profils = $profils->whereIn('id', [1, 2]); // IDs Élève(1) et Parent(2)

            // Récupération des classes...
        } else {
            $profils = $profils; // Tous les profils
        }

        // Finaliser la requête
        $profils = $profils->pluck('libelle', 'id')->toArray();

        if ($userProfil && isset($userProfil->personnel)) {
            // L'utilisateur est un enseignant
            $isEnseignant = true;
            $enseignantId = $userProfil->personnel;

            $classes = DB::table('affectation_matiere')
                ->where('enseignant', $enseignantId)
                ->pluck('classe')
                ->toArray();

            $classes = Classe::whereIn('id', $classes)->pluck('libelle', 'id')->toArray();
        } else {
            // L'utilisateur est un administrateur, récupérer toutes les classes
            $classes = Classe::pluck('libelle', 'id')->toArray();
        }

        $transport = old('transport', 'email');

        return view('correspondances.create', compact(
            'profils',
            'classes',
            'transport',
            'isEnseignant' // S'assurer qu'il est bien inclus
        ));
    }


    /**
     * Store a newly created Correspondance in storage.
     */

    public function getUserClasses()
    {
        try {
            $user = auth()->user();
            $userProfil = DB::table('user_profil')->where('user', $user->id)->first();

            // Vérification du rôle
            if (!$userProfil || !isset($userProfil->personnel)) {
                return response()->json(['classes' => Classe::pluck('libelle', 'id'), 'is_enseignant' => false]);
            }

            // Cas enseignant
            $enseignantId = $userProfil->personnel;
            $classes = DB::table('affectation_matiere')
                ->where('enseignant', $enseignantId)
                ->pluck('classe')
                ->toArray();

            $classesLibelles = count($classes) > 0
                ? Classe::whereIn('id', $classes)->pluck('libelle', 'id')
                : collect([]);

            return response()->json([
                'classes' => $classesLibelles,
                'is_enseignant' => true
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getRecipients(Request $request)
    {
        // Modifiez la validation :
        $validated = $request->validate([
            'cible' => 'required|numeric',
            // 'transport' => 'required|in:email', // À supprimer
            'classe_id' => 'nullable|numeric'
        ]);

        $profil = Profil::findOrFail($validated['cible']);
        $recipients = [];

        switch ($profil->id) {
            case 1: // Élèves
                $currentYear = AnneeScolaire::where('en_cours', true)->firstOrFail();
                $query = Effectif::with('eleves')
                    ->where('annee_scolaire', $currentYear->id)
                    ->when($validated['classe_id'] != 0, function ($q) use ($validated) {
                        return $q->where('classe', $validated['classe_id']);
                    });
                $recipients = $query->get()
                    ->pluck('eleves.' . 'email')
                    ->filter()
                    ->toArray();
                break;

            case 2: // Parents
                if ($validated['classe_id'] && $validated['classe_id'] != 0) {
                    // Logique avec classe spécifique
                    $eleveIds = Effectif::where('classe', $validated['classe_id'])
                        ->pluck('eleve')
                        ->toArray();

                    $parentIds = Eleve::whereIn('id', $eleveIds)
                        ->pluck('parent')
                        ->filter()
                        ->toArray();

                    $recipients = Parents::whereIn('id', $parentIds)
                        ->whereNotNull('email')
                        ->pluck('email')
                        ->toArray();
                } else {
                    // Toutes les classes
                    $recipients = Parents::whereNotNull('email')
                        ->pluck('email')
                        ->toArray();
                }
                break;

            case 3: // Personnel
                $userIds = UserProfil::where('profil', $profil->id)
                    ->whereNull('eleve')
                    ->whereNull('parent')
                    ->whereNull('personnel')
                    ->pluck('user');
                $recipients = User::whereIn('id', $userIds)
                    ->where('id', '!=', auth()->id())
                    ->whereNotNull('email')
                    ->pluck('email')
                    ->toArray();
                break;

            default:
                $recipients = User::where('profil', $profil->id)
                    ->whereNotNull('email')
                    ->pluck('email')
                    ->toArray();
                break;
        }

        return response()->json([
            'destinataires' => implode(', ', $recipients)
        ]);
    }

    public function store(CreateCorrespondanceRequest $request)
    {
        DB::beginTransaction();
        try {
            // Récupérer les inputs
            $input = $request->all();
            $input['expediteur'] = auth()->id();
            $input['destinataire'] = 'Liste complète dans les logs'; // Optionnel
            $input['transport'] = 'email'; // Ajoutez cette ligne
            // Ici, le transport est fixé sur 'email'

            $profil = Profil::findOrFail($input['cible']);
            $recipients = [];

            switch ($profil->id) {
                case 1: // Élèves
                    $request->validate(['classe_id' => 'required|numeric']);
                    $currentYear = AnneeScolaire::where('en_cours', true)->firstOrFail();
                    $query = Effectif::with('eleves')
                        ->where('annee_scolaire', $currentYear->id)
                        ->when($request->classe_id != 0, function ($q) use ($request) {
                            return $q->where('classe', $request->classe_id);
                        });
                    $recipients = $query->get()
                        ->pluck('eleves.email')
                        ->filter()
                        ->toArray();
                    break;

                case 2: // Parents
                    if ($request->classe_id && $request->classe_id != 0) {
                        $eleveIds = Effectif::where('classe', $request->classe_id)
                            ->pluck('eleve')
                            ->toArray();

                        $parentIds = Eleve::whereIn('id', $eleveIds)
                            ->pluck('parent')
                            ->filter()
                            ->toArray();

                        $recipients = Parents::whereIn('id', $parentIds)
                            ->whereNotNull('email')
                            ->pluck('email')
                            ->toArray();
                    } else {
                        $recipients = Parents::whereNotNull('email')
                            ->pluck('email')
                            ->toArray();
                    }
                    break;

                case 3: // Personnel
                    $userIds = UserProfil::where('profil', $profil->id)
                        ->whereNull('eleve')
                        ->whereNull('parent')
                        ->whereNull('personnel')
                        ->pluck('user');
                    $recipients = User::whereIn('id', $userIds)
                        ->where('id', '!=', auth()->id())
                        ->whereNotNull('email')
                        ->pluck('email')
                        ->toArray();
                    break;

                default:
                    $recipients = User::where('profil', $profil->id)
                        ->whereNotNull('email')
                        ->pluck('email')
                        ->toArray();
                    break;
            }

            // Remplit automatiquement le champ destinataire avec la liste récupérée
            $input['destinataire'] = implode(', ', $recipients);

            // Validation du message (email n'a pas de contrainte de 160 caractères)
            $request->validate([
                'message' => 'required|string',
            ]);

            $correspondance = $this->correspondanceRepository->create($input);

            // Préparation des données pour l'envoi d'email
            $mailData = [
                'nom'     => auth()->user()->name,
                'subject' => $correspondance->objet,
                'email'   => auth()->user()->email,
                'message' => $correspondance->message,
            ];

            if (!empty($recipients)) {
                // Envoi d'email par lots (50 destinataires maximum par envoi)
                foreach (array_chunk($recipients, 50) as $chunk) {
                    Mail::to($chunk)
                        ->send(new CorrespondanceMail(array_merge($mailData, [
                            'email' => auth()->user()->email,
                        ])));
                }
            }

            DB::commit();

            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified Correspondance.
     */
    public function show($id)
    {
        $correspondance = $this->correspondanceRepository->find($id);

        if (empty($correspondance)) {
            Flash::error('Correspondance not found');

            return redirect(route('correspondances.index'));
        }

        return view('correspondances.show')->with('correspondance', $correspondance);
    }

    /**
     * Show the form for editing the specified Correspondance.
     */
    public function edit($id)
    {
        $correspondance = $this->correspondanceRepository->find($id);

        if (empty($correspondance)) {
            Flash::error('Correspondance non trouvée');

            return redirect(route('correspondances.index'));
        }

        $profils = Profil::pluck('libelle', 'id')->toArray(); // Conversion en tableau
        $classes = Classe::pluck('libelle', 'id')->toArray(); // Conversion en tableau
        $transport = old('transport', 'email'); // Valeur par défaut : email

        return view('correspondances.edit')->with('correspondance', $correspondance)->with(compact('profils', 'classes
        '));
    }

    /**
     * Update the specified Correspondance in storage.
     */
    public function update($id, UpdateCorrespondanceRequest $request)
    {
        $correspondance = $this->correspondanceRepository->find($id);

        if (empty($correspondance)) {
            Flash::error('Correspondance non trouvé');

            return redirect(route('correspondances.index'));
        }

        $correspondance = $this->correspondanceRepository->update($request->all(), $id);

        Flash::success('Mise à jour effectuée avec succès!');

        return redirect(route('correspondances.index'));
    }

    /**
     * Remove the specified Correspondance from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $correspondance = $this->correspondanceRepository->find($id);

        if (empty($correspondance)) {
            Flash::error('Correspondance non trouvée');

            return redirect(route('correspondances.index'));
        }

        $this->correspondanceRepository->delete($id);

        Flash::success('Suppression effectuée avec succès!');

        return redirect(route('correspondances.index'));
    }
}
