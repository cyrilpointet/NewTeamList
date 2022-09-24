<div>
    <h1 class="text-center"><strong>Team List</strong> admin</h1>
    <div class="row">
        <div class="col-12 col-lg-4 col-lg-offset-4">
            <div class="panel">
                <div class="panel-body">
                    <h3>Quelques nombres</h3>
                    <ul class="list-group">
                        <li class="list-group-item">
                            Utilisateurs
                            <span class="badge">
                                {{ $users }}
                            </span>
                        </li>
                        <li class="list-group-item">
                            Listes
                            <span class="badge">
                                {{ $teams }}
                            </span>
                        </li>
                        <li class="list-group-item">
                            Produits
                            <span class="badge">
                                {{ $posts }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
