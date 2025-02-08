<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControleController;
use App\Http\Controllers\CorrespondanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
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


//fields dynamique pour correspondance
Route::any('/user-classes', [CorrespondanceController::class, 'getUserClasses']);
Route::get('/get-recipients', [\App\Http\Controllers\CorrespondanceController::class, 'getRecipients'])->name('get.recipients');
Route::get('correspondances/get-recipients', [App\Http\Controllers\CorrespondanceController::class, 'getRecipients'])
     ->name('correspondances.getRecipients');

