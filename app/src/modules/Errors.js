export default function errorHandler(err, context) {
    if (err.response.data) {
        switch(err.response.data.error) {
            case 400:
            case 415:
            case 413:
                context.fail = err.response.data.message;
                break;
            case 401:
                if (err.response.data.message.toLowerCase().includes('token')) {
                    context.$router.push('/signIn');
                } else {
                    context.fail = err.response.data.message;
                }
                break;
            case 500:
                context.fail = err.response.data.message;
                break;
            default:
                console.log(err.response.data.message);
        }
    } else {
        console.log(err);
    }
}