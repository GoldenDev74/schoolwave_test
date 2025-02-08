<?php

use Illuminate\Support\Facades\Route;
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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
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

//fields dynamique pour correspondance
Route::any('/user-classes', [CorrespondanceController::class, 'getUserClasses']);
Route::get('/get-recipients', [\App\Http\Controllers\CorrespondanceController::class, 'getRecipients'])->name('get.recipients');
Route::get('correspondances/get-recipients', [App\Http\Controllers\CorrespondanceController::class, 'getRecipients'])
     ->name('correspondances.getRecipients');

Route::get('controles/{controle}', [ControleController::class, 'show'])->name('controles.show');
