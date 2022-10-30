<?php
//$_topMenu = '';
include DIR.'/app/templates/topmenu_layout.php';

function htmlHead() {}
function bodyEnd() {}
function mainContent() { ?>
<div class="ui container">
    <h1>Lolok App</h1>
    <p>Hi. Welcome to Lolok App.</p>
    <hr />
    <p>This framework use PHP, Fandisus\Lolok package, Vue JS, Fomantic UI</p>
    <ul>
        <li>PHP</li>
        <li>Fandisus/Lolok composer package for DB and model purpose</li>
        <li>vanilla/garden-cli composer package for lolok cli with <a href='https://github.com/vanilla/garden-cli/issues/42'>known issues</a></li>
        <li>Vue JS</li>
        <li>Fomantic UI + jQuery</li>
        <li>Momentjs</li>
        <li>Lodash</li>
    </ul>
    <p>On the menubar is the login button to the example login page. You can also use the login button below.</p>
    <a class="ui blue button" href="<?= WEBHOME ?>login" role="button">Login</a>
</div>
<?php }