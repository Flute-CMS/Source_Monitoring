function copyIpToClipboard(elementId) {
    try {
        var textToCopy = document.getElementById(elementId);
        var temporaryElement = document.createElement('textarea');
        temporaryElement.style.position = 'absolute';
        temporaryElement.style.left = '-9999px';
        temporaryElement.setAttribute('readonly', '');
        temporaryElement.value = 'connect ' + (textToCopy.innerText || textToCopy.value);
        document.body.appendChild(temporaryElement);
        temporaryElement.select();
        document.execCommand('copy');
        document.body.removeChild(temporaryElement);
    
        toast({
            type: 'success',
            message: translate('monitoring.copy_ip.success')
        });
    } catch(error) {
        toast({
            type: 'error',
            message: translate('monitoring.copy_ip.error')
        });
    }
}