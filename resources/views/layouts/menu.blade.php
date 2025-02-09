<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Tableau de bord</p>
    </a>
</li>

<!-- Gestion des cours -->
<li class="nav-item has-treeview {{ Request::is('matieres*', 'categorieMatieres*', 'typeCours*', 'typeExamens*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-book"></i>
        <p>
            Gestion des cours
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview" style="padding-left: 15px;">
        <li class="nav-item">
            <a href="{{ route('matieres.index') }}" class="nav-link {{ Request::is('matieres*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-book-open"></i>
                <p>Matières</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('categorieMatieres.index') }}" class="nav-link {{ Request::is('categorieMatieres*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tag"></i>
                <p>Catégories matières</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('typeCours.index') }}" class="nav-link {{ Request::is('typeCours*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-graduation-cap"></i>
                <p>Type cours</p>
            </a>
        </li>
    </ul>
</li>

<!-- Gestion des Examens -->
<li class="nav-item has-treeview {{ Request::is('examens*', 'typeExamens*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-file-alt"></i>
        <p>
            Gestion des Examens
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview" style="padding-left: 15px;">
        <li class="nav-item">
            <a href="{{ route('examens.index') }}" class="nav-link {{ Request::is('examens*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-pencil-alt"></i>
                <p>Examens</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('typeExamens.index') }}" class="nav-link {{ Request::is('typeExamens*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-clipboard-list"></i>
                <p>Type Examens</p>
            </a>
        </li>
    </ul>
</li>

<!-- Gestion des Enseignants -->
<li class="nav-item has-treeview {{ Request::is('enseignants*', 'affectationMatieres*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-chalkboard-teacher"></i>
        <p>
            Gestion des Enseignants
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview" style="padding-left: 15px;">
        <li class="nav-item">
            <a href="{{ route('enseignants.index') }}" class="nav-link {{ Request::is('enseignants*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-tie"></i>
            <p>Enseignants</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('affectationMatieres.index') }}" class="nav-link {{ Request::is('affectation-matieres*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tasks"></i>
            <p>Affectations Matières</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('mesAffectationMatieres.index') }}" class="nav-link {{ Request::is('mesAffectationMatieres*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tasks"></i>
                <p>Mes Affectations Matières</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('suiviCoursEnseignant.index') }}" class="nav-link {{ Request::is('suiviCoursEnseignant*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-eye"></i>
            <p>Suivi Cours Enseignant</p>
            </a>
        </li>
    </ul>
</li>

<!-- Gestion des Etudiants -->
<li class="nav-item has-treeview {{ Request::is('eleves*', 'meseleves*', 'parents*', 'lienParentEleves*', 'effectifs*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-users"></i>
        <p>
            Gestion des Etudiants
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview" style="padding-left: 15px;">

        <li class="nav-item">
            <a href="{{ route('eleves.index') }}" class="nav-link {{ Request::is('eleves*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-graduate"></i>
                <p>Elèves</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('meseleves.index') }}" class="nav-link {{ Request::is('meseleves*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-graduate"></i>
                <p>Mes Elèves</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('parents.index') }}" class="nav-link {{ Request::is('parents*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-friends"></i>
                <p>Parents</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('lienParentEleves.index') }}" class="nav-link {{ Request::is('lienParentEleves*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-link"></i>
                <p>Lien Parent Elève</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('effectifs.index') }}" class="nav-link {{ Request::is('effectifs*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>Effectifs</p>
            </a>
        </li>
    </ul>
</li>

<!-- Gestion des Salles et Horaires -->
<li class="nav-item has-treeview {{ Request::is('classes*', 'salles*', 'horaires*', 'jourSemaines*', 'modeAffectations*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-clock"></i>
        <p>
            Gestion des Salles et Horaires
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview" style="padding-left: 15px;">
        <li class="nav-item">
            <a href="{{ route('classes.index') }}" class="nav-link {{ Request::is('classes*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-chalkboard"></i>
                <p>Classes</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('salles.index') }}" class="nav-link {{ Request::is('salles*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-building"></i>
                <p>Salles</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('horaires.index') }}" class="nav-link {{ Request::is('horaires*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-clock"></i>
                <p>Horaires</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('jourSemaines.index') }}" class="nav-link {{ Request::is('jourSemaines*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-calendar-day"></i>
                <p>Jours Semaines</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('modeAffectations.index') }}" class="nav-link {{ Request::is('modeAffectations*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-random"></i>
                <p>Mode Affectations</p>
            </a>
        </li>
    </ul>
</li>

<!-- Gestion des Utilisateurs -->
<li class="nav-item has-treeview {{ Request::is('users*', 'userLiens*', 'sexes*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-users-cog"></i>
        <p>
            Gestion des Utilisateurs
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview" style="padding-left: 15px;">
        <li class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user"></i>
                <p>Users</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('userLiens.index') }}" class="nav-link {{ Request::is('userLiens*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-link"></i>
                <p>Users Liens</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('sexes.index') }}" class="nav-link {{ Request::is('sexes*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-venus-mars"></i>
                <p>Sexes</p>
            </a>
        </li>
    </ul>
</li>

<!-- Gestion des Diplômes -->
<li class="nav-item">
    <a href="{{ route('diplomes.index') }}" class="nav-link {{ Request::is('diplomes*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-certificate"></i>
        <p>Gestion des Diplômes</p>
    </a>
</li>

<!-- Gestion des Années Scolaires -->
<li class="nav-item">
    <a href="{{ route('anneeScolaires.index') }}" class="nav-link {{ Request::is('anneeScolaires*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-calendar"></i>
        <p>Années Scolaires</p>
    </a>
</li>

<!-- Gestion des Filières -->
<li class="nav-item">
    <a href="{{ route('fileres.index') }}" class="nav-link {{ Request::is('fileres*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-project-diagram"></i>
        <p>Filières</p>
    </a>
</li>

<!-- Gestion des Pays -->
<li class="nav-item">
    <a href="{{ route('pays.index') }}" class="nav-link {{ Request::is('pays*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-globe"></i>
        <p>Pays</p>
    </a>
</li>

<!-- Gestion des Contrôles -->
<!-- Gestion des Utilisateurs -->
<li class="nav-item has-treeview {{ Request::is('controles*', 'enseignantcontroles*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-users-cog"></i>
        <p>
            Gestion des Controles
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview" style="padding-left: 15px;">
        <li class="nav-item">
            <a href="{{ route('controles.index') }}" class="nav-link {{ Request::is('controles*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tasks"></i>
                <p>Contrôles</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('enseignantcontroles.index') }}" class="nav-link {{ Request::is('enseignantcontroles*') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tasks"></i>
                <p>Contrôles Enseignant</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="{{ route('typepersonnels.index') }}" class="nav-link {{ Request::is('typepersonnels*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-briefcase"></i>
        <p>Typepersonnels</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('profils.index') }}" class="nav-link {{ Request::is('profils*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-id-card"></i>
        <p>Profils</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('userProfils.index') }}" class="nav-link {{ Request::is('userProfils*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-id-card-alt"></i>
        <p>User Profils</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('correspondances.index') }}" class="nav-link {{ Request::is('correspondances*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-envelope"></i>
        <p>Correspondances</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('suivi_cours.index') }}" class="nav-link {{ Request::is('suiviCours*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-eye"></i>
        <p>Suivi Cours</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('suiviCoursParents.index') }}" class="nav-link {{ Request::is('suiviCoursParents*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-friends"></i>
        <p>Suivi des Cours Parents</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('suiviCoursEleves.index') }}" class="nav-link {{ Request::is('suiviCoursEleves*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-user-graduate"></i>
        <p>Suivi des Cours Eleves</p>
    </a>
</li>

