<?php

namespace App\Http\Controllers;

use App\DataTables\EnseignantDataTable;
use App\Http\Requests\CreateEnseignantRequest;
use App\Http\Requests\UpdateEnseignantRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\EnseignantRepository;
use Illuminate\Http\Request;
use Flash;
use App\Models\User;
use App\Models\UserProfil;
use App\Models\Sexe;
use App\Models\Diplome;
use App\Models\Pays;
use App\Models\Filere;
use App\Models\TypeCours;
use App\Models\TypePersonnel;
use App\Models\Profil;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DB;
use App\Mail\Contact;
use Illuminate\Support\Facades\Mail;

class EnseignantController extends AppBaseController
{
    /** @var EnseignantRepository $enseignantRepository*/
    private $enseignantRepository;

    public function __construct(EnseignantRepository $enseignantRepo)
    {
        $this->enseignantRepository = $enseignantRepo;
    }

    /**
     * Display a listing of the Enseignant.
     */
    public function index(EnseignantDataTable $enseignantDataTable)
    {
        return $enseignantDataTable->render('enseignants.index');
    }


    /**
     * Show the form for creating a new Enseignant.
     */
    public function create()
    {
        $typeCours = TypeCours::pluck('libelle', 'id');
        $sexes = Sexe::pluck('libelle', 'id');
        $diplomes = Diplome::pluck('libelle', 'id');
        $filieres = Filere::pluck('libelle', 'id');
        $pays = Pays::pluck('libelle', 'id');
        $nationalites = Pays::pluck('libelle', 'id');
        $typePersonnels = TypePersonnel::pluck('libelle', 'id');
        return view('enseignants.create')->with('sexes', $sexes)->with('diplomes', $diplomes)->with('filieres', $filieres)->with('typeCours', $typeCours)->with('pays', $pays)->with('nationalites', $nationalites)->with('typePersonnels', $typePersonnels);
    }

    /**
     * Store a newly created Enseignant in storage.
     */
    public function store(CreateEnseignantRequest $request)
    {
        try {
            DB::beginTransaction();

            $input = $request->all();

            // Création de l'enseignant
            try {
                $data = [
                    'nom_prenom' => $input['nom_prenom'],
                    'date_naissance' => date('Y-m-d', strtotime($input['date_naissance'])),
                    'date_engagement' => date('Y-m-d', strtotime($input['date_engagement'])),
                    'date_diplome' => date('Y-m-d', strtotime($input['date_diplome'])),
                    'diplome' => $input['diplome'],
                    'filiere' => $input['filiere'],
                    'sexe' => $input['sexe'],
                    'type_cours' => $input['type_cours'],
                    'nationalite' => $input['nationalite'],
                    'email' => $input['email'],
                    'enseignant' => $input['enseignant'] ?? 0,
                    'administration' => $input['administration'] ?? 0,
                    'type_personnel' => isset($input['type_personnel']) ? $input['type_personnel'] : null,
                ];
                
                $enseignant = $this->enseignantRepository->create($data);
            } catch (\Exception $e) {
                throw $e;
            }

            // Création de l'utilisateur
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

            // Création du profil Utilisateur (Personnel)
            try {
                $profilPersonnel = Profil::where('libelle', 'Personnel')->first();
                if (!$profilPersonnel) {
                    throw new \Exception("Le profil Personnel n'existe pas.");
                }

                UserProfil::create([
                    'user' => $user->id,
                    'profil' => $profilPersonnel->id,
                    'personnel' => $enseignant->id,
                ]);
            } catch (\Exception $e) {
                throw $e;
            }

            DB::commit();
            Flash::success('Enseignant enregistré avec succès.');

            return redirect(route('enseignants.index'));
        } catch (\Exception $e) {
            DB::rollBack();
            Flash::error('Une erreur est survenue lors de l\'enregistrement de l\'enseignant: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified Enseignant.
     */
    public function show($id)
    {
        $enseignant = $this->enseignantRepository->find($id);

        if (empty($enseignant)) {
            Flash::error('Enseignant non trouvé');

            return redirect(route('enseignants.index'));
        }

        return view('enseignants.show')->with('enseignant', $enseignant);
    }

    /**
     * Show the form for editing the specified Enseignant.
     */
    public function edit($id)
    {
        $enseignant = $this->enseignantRepository->find($id);

        if (empty($enseignant)) {
            Flash::error('Enseignant non trouvé');

            return redirect(route('enseignants.index'));
        }
        $typeCours = TypeCours::pluck('libelle', 'id');
        $sexes = Sexe::pluck('libelle', 'id');
        $diplomes = Diplome::pluck('libelle', 'id');
        $filieres = Filere::pluck('libelle', 'id');
        $pays = Pays::pluck('libelle', 'id');
        $nationalites = Pays::pluck('libelle', 'id');
        $typePersonnels = TypePersonnel::pluck('libelle', 'id');    
        return view('enseignants.create')->with('sexes', $sexes)->with('diplomes', $diplomes)->with('filieres', $filieres)->with('typeCours', $typeCours)->with('pays', $pays)->with('nationalites', $nationalites)->with('typePersonnels', $typePersonnels) ;   
    }

    /**
     * Update the specified Enseignant in storage.
     */
    public function update($id, UpdateEnseignantRequest $request)
    {
        $enseignant = $this->enseignantRepository->find($id);

        if (empty($enseignant)) {
            Flash::error('Enseignant non trouvé');

            return redirect(route('enseignants.index'));
        }

        $enseignant = $this->enseignantRepository->update($request->all(), $id);

        Flash::success('Enseignant mise à jour avec succès.');

        return redirect(route('enseignants.index'));
    }

    /**
     * Remove the specified Enseignant from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $enseignant = $this->enseignantRepository->find($id);

        if (empty($enseignant)) {
            Flash::error('Enseignant non trouvé');

            return redirect(route('enseignants.index'));
        }

        $this->enseignantRepository->delete($id);

        Flash::success('Enseignant Supprimé avec succès.');

        return redirect(route('enseignants.index'));
    }
}
