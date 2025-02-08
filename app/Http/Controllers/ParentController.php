<?php

namespace App\Http\Controllers;

use App\DataTables\ParentDataTable;
use App\Http\Requests\CreateParentRequest;
use App\Http\Requests\UpdateParentRequest;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ParentRepository;
use App\Models\User;
use App\Models\UserProfil;
use App\Models\Profil;
use App\Models\Parents;
use App\Models\Pays;
use App\Models\LienParentEleve;
use App\Models\Sexe;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DB;
use App\Mail\Contact;
use Illuminate\Support\Facades\Mail;

class ParentController extends AppBaseController
{
    /** @var ParentRepository $parentRepository*/
    private $parentRepository;

    public function __construct(ParentRepository $parentRepo)
    {
        $this->parentRepository = $parentRepo;
    }

    /**
     * Display a listing of the Parent.
     */
    public function index(ParentDataTable $parentDataTable)
    {
    return $parentDataTable->render('parents.index');
    }


    /**
     * Show the form for creating a new Parent.
     */
    public function create()
    {
        $lienEleves = LienParentEleve::pluck('libelle', 'id');
        $pays_residence = Pays::pluck('libelle', 'id');
        $nationalite = Pays::pluck('libelle', 'id');
        return view('parents.create')->with('lienEleves', $lienEleves)->with('pays_residence', $pays_residence)->with('nationalite', $nationalite);
    }

    /**
     * Store a newly created Parent in storage.
     */
    public function store(CreateParentRequest $request)
    {
        try {
            DB::beginTransaction();

            $input = $request->all();

            // Créer le parent
            try {
                $data = [
                    'nom_prenom' => $input['nom_prenom'],
                    'date_naissance' => date('Y-m-d', strtotime($input['date_naissance'])),
                    'lieu_naissance' => $input['lieu_naissance'],
                    'nationalite' => $input['nationalite'],
                    'adresse' => $input['adresse'],
                    'ville' => $input['ville'],
                    'pays_residence' => $input['pays_residence'],
                    'telephone' => $input['telephone'],
                    'email' => $input['email'],
                    'lien_eleve' => $input['lien_eleve']
                ];
                
                $parent = $this->parentRepository->create($data);
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

            // Créer le profil Parent
            try {
                $profilParent = Profil::where('libelle', 'Parent')->first();
                if (!$profilParent) {
                    throw new \Exception('Le profil Parent n\'existe pas');
                }

                UserProfil::create([
                    'user' => $user->id,
                    'profil' => $profilParent->id,
                    'parent' => $parent->id
                ]);
            } catch (\Exception $e) {
                throw $e;
            }

            DB::commit();
            Flash::success('Parent enregistré avec succès.');

            return redirect(route('parents.index'));
        } catch (\Exception $e) {
            DB::rollBack();
            Flash::error('Une erreur est survenue lors de l\'enregistrement du parent: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified Parent.
     */
    public function show($id)
    {
        $parent = $this->parentRepository->find($id);

        if (empty($parent)) {
            Flash::error('Parent not found');

            return redirect(route('parents.index'));
        }

        return view('parents.show')->with('parent', $parent);
    }

    /**
     * Show the form for editing the specified Parent.
     */
    public function edit($id)
    {
        $parent = $this->parentRepository->find($id);

        if (empty($parent)) {
            Flash::error('Parent not found');

            return redirect(route('parents.index'));
        }
        
        $lienEleves = LienParentEleve::pluck('libelle', 'id');
        $pays_residence = Pays::pluck('libelle', 'id');
        $nationalite = Pays::pluck('libelle', 'id');
        return view('parents.edit')->with('parent', $parent)->with('lienEleves', $lienEleves)->with('pays_residence', $pays_residence)->with('nationalite', $nationalite);
    }

    /**
     * Update the specified Parent in storage.
     */
    public function update($id, UpdateParentRequest $request)
    {
        $parent = $this->parentRepository->find($id);

        if (empty($parent)) {
            Flash::error('Parent not found');

            return redirect(route('parents.index'));
        }

        $parent = $this->parentRepository->update($request->all(), $id);

        Flash::success('Mise à jour éffectué avec succès.');

        return redirect(route('parents.index'));
    }

    /**
     * Remove the specified Parent from storage.
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        $parent = $this->parentRepository->find($id);

        if (empty($parent)) {
            Flash::error('Parent non trouvé');

            return redirect(route('parents.index'));
        }

        $this->parentRepository->delete($id);

        Flash::success('Suppréssion éffectué avec succès.');

        return redirect(route('parents.index'));
    }
}