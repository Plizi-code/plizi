<template>
    <div class="photoalbum-item d-flex my-0 mx-auto">
            <router-link :to="{ path: `/user-${userId}/album-${album.id}`, params: {userId: userId, albumId: album.id} }"
                         tag="a"
                         class="photoalbum-item-link position-relative"
                         :album="album">
                <img class="photoalbum-item-img"
                     :src="image"
                     :alt="album.title"
                     :title="album.title"/>
                <div class="photoalbum-item-title">
                    <h6>
                        {{ album.title }}
                    </h6>
                </div>
            </router-link>
    </div>
</template>

<script>

export default {
name: 'PhotoalbumItem',
props: {
    album: Object,
    userId: String
},
computed: {
    image() {
        return this.album.lastImage ? this.album.lastImage.image.original.path : '../../images/noavatar-256.png';
    },
},
}
</script>

