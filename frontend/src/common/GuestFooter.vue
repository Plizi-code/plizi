<template>
    <div class="container-fluid">
        <div class="row">
            <footer class="navbar plz-footer col-12 w-100 container-fluid m-0 px-0 px-sm-3">
                <div class="plz-bottom-links w-100 d-flex flex-wrap  align-items-center justify-content-between justify-content-md-start  ">
                    <div class="plz-bottom-link">
                        <router-link to="/about" tag="div" class="nav-item text-center text-md-left pl-md-0"><a class="nav-link pl-md-0">О проекте</a></router-link>
                    </div>
                    <div class="plz-bottom-link">
                        <router-link to="/rules" tag="div" class="nav-item text-center text-md-left"><a class="nav-link">Правила</a></router-link>
                    </div>
                    <div class="plz-bottom-link">
                        <router-link to="/advertisement" tag="div" class="nav-item text-center text-md-left"><a class="nav-link">Реклама</a></router-link>
                    </div>
                    <div class="plz-bottom-link">
                        <router-link to="/for-developers" tag="div" class="nav-item text-center text-md-left"><a class="nav-link">Для разработчиков</a></router-link>
                    </div>

                    <div class="plz-bottom-link text-center"  style="display: none;">
                        <span class="nav-link dropdown-menu-btn  text-center text-md-left dropup d-inline-block" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Русский
                            <i class="fas fa-chevron-down ml-1"></i>
                            <span class="dropdown-menu py-3 px-0" aria-labelledby="dropdownMenuButton">
                                <router-link to="/switch-language" tag="div" class="nav-item">
                                    <a class="dropdown-item" href="#">English</a>
                                </router-link>
                                <router-link to="/switch-language" tag="div" class="nav-item">
                                    <a class="dropdown-item" href="#">German</a>
                                </router-link>
                            </span>
                        </span>
                    </div>

                    <div class="plz-bottom-link align-self-end text-center text-md-right ml-auto mr-0">
                        <router-link to="/switch-language" tag="div" class="nav-item"><a class="nav-link plz-copy px-0">&copy; Plizi {{ (new Date()).getFullYear() }}</a></router-link>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AuthFooter',
    data () {
        return {

        }
    }
}
</script>
