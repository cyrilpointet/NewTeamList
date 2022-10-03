<mjml>
    <mj-body>
        <mj-section background-color="#c97a03">
            <mj-column>
                <mj-text font-size="20px" color="#FFFFFF" font-family="helvetica" align="center">TEAMLIST</mj-text>
            </mj-column>
        </mj-section>
        <mj-section>
            <mj-column>
                <mj-text font-size="16px">Bonjour</mj-text>
                <mj-text font-size="16px"><strong>{{ $mailData['name'] }}</strong> souhaite partager une liste avec vous dans <strong>Team List</strong> !</mj-text>
                <mj-text font-size="16px">TeamList est une application gratuite de partage de TODO listes.</mj-text>
                <mj-text font-size="16px">Vous pouvez vous inscrire en suivant le lien ci-dessous :</mj-text>
                <mj-button href="{{ $mailData['link'] }}" font-family="Helvetica" background-color="#c97a03" color="white">
                    Visiter TeamList
                </mj-button>
            </mj-column>
        </mj-section>
    </mj-body>
</mjml>
