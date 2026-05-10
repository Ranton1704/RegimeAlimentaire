<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Regime Santé') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<header>
    <div class="brand">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2a9 9 0 0 1 9 9c0 4-2.5 7.5-6 9.5a9 9 0 0 1-6 0C5.5 18.5 3 15 3 11a9 9 0 0 1 9-9z"/>
                <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                <line x1="9" y1="9" x2="9.01" y2="9"/>
                <line x1="15" y1="9" x2="15.01" y2="9"/>
            </svg>
        </div>
        <h1>Regime Santé <span class="brand-id">ETU004371-4242</span></h1>
    </div>

    <nav>
        <?php if (session()->get('isLoggedIn')): ?>
            <?php $isAdmin = strtoupper((string) (session()->get('role') ?? 'USER')) === 'ADMIN'; ?>
            <a href="/dashboard" class="<?= (current_url(true)->getPath() === '/dashboard') ? 'active' : '' ?>">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
                <?= $isAdmin ? 'Admin' : 'Dashboard' ?>
            </a>
            <?php if ($isAdmin): ?>
                <a href="/gestion/parametres" class="<?= (str_contains(current_url(true)->getPath(), '/gestion/parametres')) ? 'active' : '' ?>">Paramètres</a>
            <?php endif; ?>
            <?php if (!$isAdmin): ?>
                <a href="/profil" class="<?= (str_contains(current_url(true)->getPath(), '/profil')) ? 'active' : '' ?>">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    Profil
                </a>
            <?php endif; ?>
            <a href="/logout" class="nav-logout">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Déconnexion
            </a>
        <?php else: ?>

        <?php endif; ?>
    </nav>
</header>