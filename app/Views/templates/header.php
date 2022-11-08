<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo esc($title) ?></title>

    <meta name="description" content="An energy product site">

    <!-- dns-prefetch opens connection with domain before fetching (performance) from [here](https://developer.mozilla.org/en-US/docs/Web/Performance/dns-prefetch) -->
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net/">
    <!-- from [here](https://icons.getbootstrap.com/#install) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/allStyling.css">
</head>

<body>
    <nav id="navbar-header">
        <a class="no-link-styling" href="/"><h1 id="logo-header">Fuelio</h1></a>
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-primary" type="submit">Search</button>
        </form>
        <ul>
            <a href="/login" class="link" >Login</a>
            <a href="/account">
                <i class="bi bi-person header-icon" aria-label="Account" ></i>
            </a>
            <a href="/basket">
                <i class="bi bi-bag header-icon" aria-label="Account" ></i>
            </a>
            <button>
                <i class="bi bi-envelope header-icon" aria-label="Notifications" ></i>
            </button>
        </ul>
    </nav>