function decodePayload(jwtToken)
{
    let splittedToken = jwtToken.split('.');
    return JSON.parse(atob(splittedToken[1]));
}