export default function errorHandler(err, context) {
    if (err.response.data) {
        switch(err.response.data.error) {
            case 400:
            case 415:
            case 413:
                alert({
                    title: "Error",
                    message: err.response.data.message,
                    okButtonText: "OK"
                });
                break;
            case 401:
                if (err.response.data.message.toLowerCase().includes('token')) {
                    context.$router.push('/signIn');
                } else {
                    alert({
                        title: "Error",
                        message: err.response.data.message,
                        okButtonText: "OK"
                    });
                }
                break;
            case 500:
                alert({
                    title: "Error",
                    message: err.response.data.message,
                    okButtonText: "OK"
                });
                break;
            default:
                console.log(err.response.data.message);
        }
    } else {
        console.log(err);
    }
}
