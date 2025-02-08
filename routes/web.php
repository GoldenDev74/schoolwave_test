<?php

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AnneeScolaireController;
use App\Http\Controllers\CategorieMatiereController;
use App\Http\Controllers\DiplomeController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\FilereController;
use App\Http\Controllers\LiensController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\SallesController;
use App\Http\Controllers\SexeController;
use App\Http\Controllers\TypeCoursController;
use App\Http\Controllers\TypeExamenController;
use App\Http\Controllers\TypeHoraireController;
use App\Http\Controllers\UserLiensController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AffectationMatiereController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\HoraireController;
use App\Http\Controllers\JourSemaineController;
use App\Http\Controllers\ModeAffectationController;
use App\Http\Controllers\LienParentEleveController;
use App\Http\Controllers\PaysController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\MesElevesController;
use App\Http\Controllers\EffectifController;
use App\Http\Controllers\ControleController;
use App\Http\Controllers\MesAffectationMatiereController;
use App\Http\Controllers\TypepersonnelController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\UserProfilController;
use App\Http\Controllers\CorrespondanceController;
use App\Http\Controllers\EnseignantControleController;
use App\Http\Controllers\SuiviCoursController;
=======
use App\Http\Controllers\ControleController;
use App\Http\Controllers\CorrespondanceController;
>>>>>>> 9557ee469115dda5e8f36788a04f70f84d7c19fc

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
<<<<<<< HEAD
| Ici se trouvent les routes web de l'application. Ces routes sont
| chargées via le RouteServiceProvider au sein du groupe "web".
|
*/

// Home et authentification
Route::get('/', [HomeController::class, 'index'])->name('home');
Auth::routes();


Route::delete('affectation-matieres/{id}', [AffectationMatiereController::class, 'destroy'])
     ->name('affectation-matieres.destroy');

// Ressources principales
Route::resource('anneeScolaires', AnneeScolaireController::class);
Route::resource('categorieMatieres', CategorieMatiereController::class);
Route::resource('diplomes', DiplomeController::class);
Route::resource('enseignants', EnseignantController::class);
Route::resource('examens', ExamenController::class);
Route::resource('fileres', FilereController::class);
Route::resource('liens', LiensController::class);
Route::resource('matieres', MatiereController::class);
Route::resource('salles', SallesController::class);
Route::resource('sexes', SexeController::class);
Route::resource('typeCours', TypeCoursController::class);
Route::resource('typeExamens', TypeExamenController::class);
Route::resource('typeHoraires', TypeHoraireController::class);
Route::resource('userLiens', UserLiensController::class);
Route::resource('users', UsersController::class);
//Route::resource('affectation-matieres', App\Http\Controllers\AffectationMatiereController::class);


// Groupe de routes pour AffectationMatiere
Route::prefix('affectationMatieres')->group(function () {

    Route::post('/annuler/{id}', [AffectationMatiereController::class, 'annuler']);

    // Affichage, création et modification
    Route::get('/', [AffectationMatiereController::class, 'index'])->name('affectationMatieres.index');
    Route::post('/', [AffectationMatiereController::class, 'store'])->name('affectationMatieres.store');
    Route::put('/{id}', [AffectationMatiereController::class, 'update'])->name('affectationMatieres.update');
    Route::any('/{id}/edit', [AffectationMatiereController::class, 'edit'])->name('affectationMatieres.edit');
    Route::get('/details/{id}', [AffectationMatiereController::class, 'getDetails'])->name('affectationMatieres.details');    
    // Supprimer une affectation
    Route::delete('/{id}', [AffectationMatiereController::class, 'destroy'])->name('affectationMatieres.destroy');
    
    // Emploi du temps et informations sur la classe
    Route::get('/emploiDuTemps', [AffectationMatiereController::class, 'emploiDuTemps'])->name('affectationMatieres.emploiDuTemps');
    Route::get('/getClasseInfo/{id}', [AffectationMatiereController::class, 'getClasseInfo'])->name('affectationMatieres.getClasseInfo');
});

// Autres ressources
Route::resource('classes', ClasseController::class);
Route::resource('horaires', HoraireController::class);
Route::resource('jourSemaines', JourSemaineController::class);
Route::resource('modeAffectations', ModeAffectationController::class);
Route::resource('lienParentEleves', LienParentEleveController::class);
Route::resource('pays', PaysController::class);
Route::resource('parents', ParentController::class);
Route::resource('eleves', EleveController::class);
Route::resource('meseleves', MesElevesController::class);
Route::resource('effectifs', EffectifController::class);
Route::resource('controles', ControleController::class);
Route::resource('enseignantcontroles', EnseignantControleController::class);

// Autres routes spécifiques
Route::get('controles/elevesByClasse/{id}', [ControleController::class, 'getElevesByClasse'])->name('controles.elevesByClasse');
Route::get('/meseleves/{eleveId}/emploi-du-temps', [MesElevesController::class, 'getClasseAffectations']);
Route::get('/api/eleves-par-classe/{classeId}', [EleveController::class, 'getElevesByClasse']);
Route::get('/eleves-par-classe/{classeId}', [MesAffectationMatiereController::class, 'getElevesByClasse'])->name('eleves.par.classe');

// Routes pour mesAffectationMatiere
Route::prefix('mesAffectationMatieres')->group(function () {
    Route::get('/', [App\Http\Controllers\MesAffectationMatiereController::class, 'index'])->name('mesAffectationMatieres.index');
   
});

// Ressources complémentaires
Route::resource('typepersonnels', TypepersonnelController::class);
Route::resource('profils', ProfilController::class);
Route::resource('userProfils', UserProfilController::class);
Route::resource('correspondances', CorrespondanceController::class);
Route::resource('suivi_cours', SuiviCoursController::class);

Route::get('controles/details', [ControleController::class, 'details'])
     ->name('controles.details')
     ->middleware('auth'); // ✅ Sécurité basique
=======
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();


Route::get('testMail', [App\Http\Controllers\HomeController::class,'testMail'])->name('testMail');
Route::resource('anneeScolaires', App\Http\Controllers\AnneeScolaireController::class);
Route::resource('categorieMatieres', App\Http\Controllers\CategorieMatiereController::class);
Route::resource('diplomes', App\Http\Controllers\DiplomeController::class);
Route::resource('enseignants', App\Http\Controllers\EnseignantController::class);
Route::resource('examens', App\Http\Controllers\ExamenController::class);
Route::resource('fileres', App\Http\Controllers\FilereController::class);
Route::resource('liens', App\Http\Controllers\LiensController::class);
Route::resource('matieres', App\Http\Controllers\MatiereController::class);
Route::resource('salles', App\Http\Controllers\SallesController::class);
Route::resource('sexes', App\Http\Controllers\SexeController::class);
Route::resource('typeCours', App\Http\Controllers\TypeCoursController::class);
Route::resource('typeExamens', App\Http\Controllers\TypeExamenController::class);
Route::resource('typeHoraires', App\Http\Controllers\TypeHoraireController::class);
Route::resource('userLiens', App\Http\Controllers\UserLiensController::class);
Route::resource('users', App\Http\Controllers\UsersController::class);
//Route::resource('affectationMatieres', App\Http\Controllers\AffectationMatiereController::class);
Route::resource('classes', App\Http\Controllers\ClasseController::class);
Route::resource('horaires', App\Http\Controllers\HoraireController::class);
Route::resource('jourSemaines', App\Http\Controllers\JourSemaineController::class);


Route::resource('modeAffectations', App\Http\Controllers\ModeAffectationController::class);
Route::resource('lienParentEleves', App\Http\Controllers\LienParentEleveController::class);
Route::resource('pays', App\Http\Controllers\PaysController::class);
Route::resource('parents', App\Http\Controllers\ParentController::class);
Route::resource('eleves', App\Http\Controllers\EleveController::class);
Route::resource('meseleves', App\Http\Controllers\MesElevesController::class);
Route::resource('effectifs', App\Http\Controllers\EffectifController::class);
Route::resource('controles', App\Http\Controllers\ControleController::class);

Route::resource('affectation-matieres', App\Http\Controllers\AffectationMatiereController::class);

// Routes pour AffectationMatiere
Route::get('affectationMatieres', [App\Http\Controllers\AffectationMatiereController::class, 'index'])->name('affectationMatieres.index');
Route::post('affectationMatieres', [App\Http\Controllers\AffectationMatiereController::class, 'store'])->name('affectationMatieres.store');
Route::get('affectationMatieres/emploiDuTemps', [App\Http\Controllers\AffectationMatiereController::class, 'emploiDuTemps'])->name('affectationMatieres.emploiDuTemps');
Route::get('affectationMatieres/getClasseInfo/{id}', [App\Http\Controllers\AffectationMatiereController::class, 'getClasseInfo'])->name('affectationMatieres.getClasseInfo');
Route::delete('affectationMatieres/{id}', [App\Http\Controllers\AffectationMatiereController::class, 'destroy'])->name('affectationMatieres.destroy');

//controles
Route::get('/controles/{enseignantId}/classes', [ControleController::class, 'getClassesByEnseignant'])->name('controles.classes');
Route::get('/controles/enseignants/{classeId}', [ControleController::class, 'getEnseignantsByClasse'])->name('controles.enseignantsByClasse');
Route::get('controles/eleves/{classeId}', [ControleController::class, 'getElevesByClasse'])->name('controles.elevesByClasse');

//Route::post('/controles/details', [ControleController::class, 'getControleDetails']);
Route::get('/controles/details', [ControleController::class, 'details'])->name('controles.details');
Route::get('controles/{controle}', [ControleController::class, 'show'])->name('controles.show');

Route::resource('typepersonnels', App\Http\Controllers\TypepersonnelController::class);
Route::resource('profils', App\Http\Controllers\ProfilController::class);
Route::resource('userProfils', App\Http\Controllers\UserProfilController::class);
Route::resource('correspondances', App\Http\Controllers\CorrespondanceController::class);
Route::resource('suivi_cours', App\Http\Controllers\SuiviCoursController::class);

>>>>>>> 9557ee469115dda5e8f36788a04f70f84d7c19fc

//fields dynamique pour correspondance
Route::any('/user-classes', [CorrespondanceController::class, 'getUserClasses']);
Route::get('/get-recipients', [\App\Http\Controllers\CorrespondanceController::class, 'getRecipients'])->name('get.recipients');
Route::get('correspondances/get-recipients', [App\Http\Controllers\CorrespondanceController::class, 'getRecipients'])
     ->name('correspondances.getRecipients');

<<<<<<< HEAD
Route::get('controles/{controle}', [ControleController::class, 'show'])->name('controles.show');
=======
>>>>>>> 9557ee469115dda5e8f36788a04f70f84d7c19fc
