<header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-primary mmd-navbar" role="navigation">
        <div class="container-fluid">
            <a class="navbar-brand mmd-logo" target="_blank" href="https://www.nsu.ru/n/mathematics-mechanics-department/">
                <img src="/image/logoMMD.png" alt="Логотип ММФ" style="height: 5vh; min-height: 55px">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <a class="navbar-brand px-lg-5" href="/">
                    <strong>Выставка фотографов мехмата</strong>
                </a>
                <ul class="navbar-nav">
                    <li class="nav-item px-xl-5">
                        <a class="nav-link<?= $navbarLinksExtraClass['feed'] ?>" href="/">Лента</a>
                    </li>
                    <li class="nav-item px-lg-5">
                        <a class="nav-link<?= $navbarLinksExtraClass['selection'] ?>" href="/exhibition">Отбор</a>
                    </li>
                    <li class="nav-item px-xl-5 pe-5">
                        <a class="nav-link<?= $navbarLinksExtraClass['profile'] ?>" href="/<?= $navbarLink3['href'] ?>"><?= $navbarLink3['name'] ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>