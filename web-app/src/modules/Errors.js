export default function errorHandler(err, context) {
    if (err.response.data) {
        switch(err.response.data.error) {
            case 400:
            case 415:
            case 413:
                displayErrorMessage(err, context);
                break;
            case 401:
                if (err.response.data.message.toLowerCase().includes('token')) {
                    context.$router.push('/signIn');
                } else {
                    displayErrorMessage(err, context);
                }
                break;
            case 500:
                displayErrorMessage(err, context);
                break;
            default:
                console.log(err.response.data.message);
        }
    } else {
        console.log(err);
    }
}

function displayErrorMessage(err, context) {
    if (context.fail !== undefined) {
        context.fail = err.response.data.message;
    } else {
        alert(err.response.data.message);
    }
}