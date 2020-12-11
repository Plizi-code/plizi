let url = window.location.href;

url = url.split('/');
url = url[0] + "//" + url[2];

const client_ids = {
    facebook: '531305747577438',
    instagram: '147643463355245',
    vk: '7398618',
    redirect_uri: url + '/login',
};

export default client_ids;
