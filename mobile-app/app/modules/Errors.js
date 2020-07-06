import SignIn from "~/views/SignIn";

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
                    context.$navigateTo(SignIn, {
                        animated: true,
                        clearHistory: true,
                        transition: {
                            name: 'slideLeft',
                            duration: 400,
                            curve: 'ease'
                        }
                    }).catch(err => console.log(err));
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
