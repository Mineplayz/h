<?php
// http://stackoverflow.com/a/15917221/107625
// fake fields are a workaround for chrome autofill getting the wrong fields
// 
// Dieses Partial hier innerhalb(!) eines <form>-Tags inkludieren.

/*
    Außerdem noch gemacht:

    - <form autocomplete="off" role="presentation">
      https://stackoverflow.com/a/39304193/107625

    - Attribute data-lpignore="true" gesetzt für LastPass.

    - Teilweise Feldbezeichnungen umgedreht; statt "email" dann "liame" und statt
      "password" dann "drowssap".
*/
?>

<div style="position: absolute; left: -500px; top: -500px;">
    <label for="fakeusernameremembered">User name</label>
    <input style="" type="text" name="fakeusernameremembered" id="fakeusernameremembered" tabindex="-1" />
    <label for="fakepasswordremembered">Password</label>
    <input style="" type="password" name="fakepasswordremembered" id="fakepasswordremembered" tabindex="-1" />
</div>