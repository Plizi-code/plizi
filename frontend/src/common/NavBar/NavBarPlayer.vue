<template>
    <div id="navbarPlayer" class="btn-block d-inline-block mt-2 ">
        <a class="btn btn-link btn-sm" href="#prev" title="предыдущий трек">
            <IconPlayerPrev/>
        </a>
        <a class="btn btn-link btn-sm" href="#play" title="начать воспроизведение">
            <IconPlayerPlay/>
        </a>
        <a class="btn btn-link btn-sm" href="#next" title="следующий трек">
            <IconPlayerNext/>
        </a>
        <a class="btn btn-link btn-song btn-sm" href="#track" title="MASE - Psychoi">
            <span class="singer">MASE</span> - <span class="song">Psychoi</span>
        </a>
    </div>
</template>

<script>
import IconPlayerNext from '../../icons/IconPlayerNext.vue';
import IconPlayerPlay from '../../icons/IconPlayerPlay.vue';
import IconPlayerPrev from '../../icons/IconPlayerPrev.vue';

export default {
    name : 'NavBarPlayer',
    components : { IconPlayerNext, IconPlayerPlay, IconPlayerPrev }
}
</script>
