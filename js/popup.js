/**
 * Centrale functie om WordPress pagina's in een strakke popup te openen
 */
function openWordPressPopup(event, url, breedte = 700, hoogte = 500) {
    if (event) event.preventDefault();
    
    // Voeg de popup-parameter toe aan de URL
    var popupUrl = url;
    if (!popupUrl.includes('popup=true')) {
        popupUrl += (popupUrl.indexOf('?') !== -1 ? '&' : '?') + 'popup=true';
    }
    
    // Bereken het exacte midden van het scherm van de bezoeker
    var links = (screen.width - breedte) / 2;
    var top = (screen.height - hoogte) / 2;
    
    // Vensterinstellingen (verbergt menu's, adresbalken en werkbalken waar mogelijk)
    var specificaties = 'width=' + breedte + ',height=' + hoogte + ',top=' + top + ',left=' + links + ',toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes';
    
    // Open het losse pop-up venster
    window.open(popupUrl, 'WordPressBlancoPopup', specificaties);
}
