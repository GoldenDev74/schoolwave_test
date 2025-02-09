<?php

namespace App\Http\Controllers;

use App\DataTables\EleveDataTable;
use App\Http\Requests\CreateEleveRequest;
use App\Http\Requests\UpdateEleveRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\EleveRepository;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfil;
use App\Models\Diplome;
use App\Models\Pays;
use App\Models\Filere;
use App\Models\TypeCours;
use App\Models\Profil;
use Illuminate\Support\Facades\Hash;
use App\Models\Parents;
use App\Models\Sexe;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\DB;
use App\Mail\Contact;
use Illuminate\Support\Facades\Mail;

class EleveController extends AppBaseController
{
    
    /** @var EleveRepository $eleveRepository*/
    private $eleveRepository;

    public function __construct(EleveRepository $eleveRepo)
    {
        $this->eleveRepository = $eleveRepo;
    }

    /**
     * Display a listing of the Eleve.
     */
    public function index(EleveDataTable $eleveDataTable)
    {
    return $eleveDataTable->render('eleves.index');
    }


    /**
     * Show the form for creating a new Eleve.
     */
    public function create()
    {
        $parents = Parents::pluck('nom_prenom', 'id')->prepend('Sélectionner un parent', '');
        $pays = Pays::pluck('libelle', 'id')->prepend('Sélectionner un pays', '');
        $nationalites = Pays::pluck('libelle', 'id')->prepend('Sélectionner une nationalité', '');
        $sexes = Sexe::pluck('libelle', 'id')->prepend('Sélectionner un sexe', '');
        return view('eleves.create')->with('parents', $parents)->with('pays', $pays)->with('nationalites', $nationalites)->with('sexes', $sexes);
    }

    /**
     * Store a newly created Eleve in storage.
     */
    public function store(CreateEleveRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $input = $request->all();

            // Créer l'élève
            try {
                $data = [
                    'nom_prenom' => $input['nom_prenom'],
                    'date_naissance' => date('Y-m-d', strtotime($input['date_naissance'])),
                    'lieu_naissance' => $input['lieu_naissance'],
                    'nationalite' => $input['nationalite'],
                    'pays_residence' => $input['pays_residence'],
                    'telephone' => $input['telephone'],
                    'email' => $input['email'],
                    'sexe' => $input['sexe'],
                    'parent' => $input['parent']
                ];
                
                $eleve = $this->eleveRepository->create($data);
            } catch (\Exception $e) {
                throw $e;
            }
            
            // Créer l'utilisateur
            try {
                $password = Str::random(10);
                $user = User::create([
                    'name' => $input['nom_prenom'],
                    'email' => $input['email'],
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(10),
                ]);

                // Envoyer l'email avec les credentials
                $emailData = [
                    'email' => $input['email'],
                    'subject' => 'Vos informations de connexion - ' . config('app.name'),
                    'message' => "Email : " . $input['email'] . "\nMot de passe : " . $password . "\n" . url('/login')
                ];
                Mail::to($input['email'])->send(new Contact($emailData));

            } catch (\Exception $e) {
                throw $e;
            }

            // Créer le profil Élève
            try {
                $profilEleve = Profil::where('libelle', 'Eleve')->first();
                if (!$profilEleve) {
                    throw new \Exception('Le profil Élève n\'existe pas');
                }

                UserProfil::create([
                    'user' => $user->id,
                    'profil' => $profilEleve->id,
                    'eleve' => $eleve->id
                ]);
            } catch (\Exception $e) {
                throw $e;
            }

            DB::commit();
            Flash::success('Élève enregistré avec succès.');

            return redirect(route('eleves.index'));
        } catch (\Exception $e) {
            DB::rollBack();
            Flash::error('Une erreur est survenue lors de l\'enregistrement de l\'élève: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified Eleve.
     */
    public function show($id)
    {
        $eleve = $this->eleveRepository->find($id);

        if (empty($eleve)) {
            Flash::error('Eleve non trouvé');

            return redirect(route('eleves.index'));
        }

        return view('eleves.show')->with('eleve', $eleve);
    }

    /**
     * Show the form for editing the specified Eleve.
     */
    public function edit($id)
    {
        $eleve = $this->eleveRepository->find($id);

        if (empty($eleve)) {
            Flash::error('Eleve non trouvé');

            return redirect(route('eleves.index'));
        }
        $parents = Parents::pluck('nom_prenom', 'id')->prepend('Sélectionner un parent', '');
        $pays = Pays::pluck('libelle', 'id')->prepend('Sélectionner un pays', '');
        $nationalites = Pays::pluck('libelle', 'id')->prepend('Sélectionner une nationalité', '');
        $sexes = Sexe::pluck('libelle', 'id')->prepend('Sélectionner un sexe', '');
        return view('eleves.edit')->with('eleve', $eleve)->with('parents', $parents)->with('pays', $pays)->with('nationalites', $nationalites)->with('sexes', $sexes);
    }

    /**
     * Update the specified Eleve in storage.
     */
    public function update($id, UpdateEleveRequest $request)
    {
        $eleve = $this->eleveRepository->find($id);

        if (empty($eleve)) {
            Flash::error('Eleve non trouvé');

            return redirect(route('eleves.index'));
        }

        $eleve = $this->eleveRepository->update($request->all(), $id);

        Flash::success('Eleve mise à jour avec succès.');

        return redirect(route('eleves.index'));
    }

    /**
     * Remove the specified Eleve from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $eleve = $this->eleveRepository->find($id);

        if (empty($eleve)) {
            Flash::error('Eleve non trouvé');

            return redirect(route('eleves.index'));
        }

        $this->eleveRepository->delete($id);

        Flash::success('Eleve supprimé avec succès.');

        return redirect(route('eleves.index'));
    }
}
