<?php if (!defined('FLUX_ROOT')) exit; ?>
<style rel="stylesheet">
    #modify-container { height: 0; overflow: hidden; }
    .zshow-form { height: auto !important; }
</style>
<script>
function toggleForm() {
    const el = document.getElementById('modify-container');
    if (el.classList.contains('zshow-form')) {
        zHideForm();
    } else {
        zShowForm();
    }
}
function zShowForm() {
    document.getElementById('modify-container').classList.add('zshow-form');
}
function zHideForm() {
    document.getElementById('modify-container').classList.remove('zshow-form');
}
function modifyToken(id) {
    const row = document.getElementById('row__token-' + id);
    if (row === undefined) {
        alert('No token found for id ' + id);
        return;
    }

    const checkboxes = document.getElementById('form__modify-form').querySelectorAll("input[type=checkbox]");
    for (let x = 0; x < checkboxes.length; ++x) {
        checkboxes[x].checked = false;
    }

    const token = row.children[1].textContent;
    const description = row.children[2].textContent;
    const capabilities = row.children[3].textContent.split(",").map(str => str.trim());
    const properties = row.children[4].textContent.split(",").map(str => str.trim().split("=")).reduce((obj, keyVal) => {obj[keyVal[0]] = keyVal[1]; return obj;}, {});

    document.getElementById('form__id').value = id;
    document.getElementById('form__token').value = token;
    document.getElementById('form__description').value = description;
    capabilities.forEach(cap => { if(cap) { document.getElementById('form__capabilities-' + cap).checked = true; } });
    Object.getOwnPropertyNames(properties).forEach(name => {
        const value = properties[name];
        document.getElementById('form__properties-' + name).value = value;
    });

    zShowForm();
    document.getElementById('jump-modify-form').scrollIntoView();
}
</script>
<h2>Zrenderer &mdash; Access tokens</h2>
<h3 id="jump-modify-form">Modify token &mdash; <a href="javascript:toggleForm()">Toggle form</a></h3>
<div id="modify-container">
    <p>
        <form method="post" id="form__modify-form" action="<?php echo $this->url('renderplayer', 'tokens') ?>">
            <table class="vertical-table">
                <tbody>
                    <tr>
                        <th col="row"><label for="form__id">ID</label></th>
                        <td><input type="text" id="form__id" name="id" readonly style="width:50px"/></td>
                        <th col="row"><label for="form__token">Token</label></th>
                        <td><input type="text" id="form__token" readonly/></td>
                    </tr>
                    <tr>
                        <th col="row"><label for="form__description">Description</label></th>
                        <td colspan="3"><input type="text" id="form__description" name="description" style="width:100%"/></td>
                    </tr>
                    <tr>
                        <th rowspan="2" col="row">Capabilities</th>
                        <td>
                            <label for="form__capabilities-readHealth">readHealth</label><input type="checkbox" id="form__capabilities-readHealth" name="capabilities-readHealth"/>
                        </td>
                        <td>
                            <label for="form__capabilities-readAccessTokens">readAccessTokens</label><input type="checkbox" id="form__capabilities-readAccessTokens" name="capabilities-readAccessTokens"/>
                        </td>
                        <td>
                            <label for="form__capabilities-modifyAccessTokens">modifyAccessTokens</label><input type="checkbox" id="form__capabilities-modifyAccessTokens" name="capabilities-modifyAccessTokens"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="form__capabilities-revokeAccessTokens">revokeAccessTokens</label><input type="checkbox" id="form__capabilities-revokeAccessTokens" name="capabilities-revokeAccessTokens"/>
                        </td>
                        <td colspan="2">
                            <label for="form__capabilities-createAccessTokens">createAccessTokens</label><input type="checkbox" id="form__capabilities-createAccessTokens" name="capabilities-createAccessTokens"/>
                        </td>
                    </tr>
                    <tr>
                        <th rowspan="2" col="row">Properties</th>
                        <th col="row"><label for="form__properties-maxJobIdsPerRequest">maxJobIdsPerRequest</label></th>
                        <td colspan="2"><input type="text" id="form__properties-maxJobIdsPerRequest" name="properties-maxJobIdsPerRequest" style="width:50px"/></td>
                    </tr>
                    <tr>
                        <th col="row"><label for="form__properties-maxRequestsPerHour">maxRequestsPerHour</label></th>
                        <td colspan="2"><input type="text" id="form__properties-maxRequestsPerHour" name="properties-maxRequestsPerHour" style="width:50px"/></td>
                    </tr>
                    <tr>
                        <td colspan="4"><input type="submit" name="modify" value="Submit"/></td>
                    </tr>
                </tbody>
            </table>
        </form>
    </p>
</div>
<p>
    <form method="post" action="<?php echo $this->url('renderplayer', 'tokens') ?>">
        <input type="submit" name="submit" onclick="this.value='Getting tokens...';" class="button" value="List tokens"/>
    </form>
</p>
<?php if (isset($_POST['submit'])): ?>
<?php if ($error): ?>
<p>
    <strong>An error occurred:</strong> <?php echo $errorMessage ?>
</p>
<?php else: ?>
<h3>Tokens</h3>
<table class="horizontal-table">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Token</th>
            <th scope="col">Description</th>
            <th scope="col">Capabilities</th>
            <th scope="col">Properties</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
<?php if (isset($tokens)): ?>
<?php foreach ($tokens as $token): ?>
        <tr id="row__token-<?php echo $token->id ?>">
            <td><?php echo $token->id ?></td>
            <td><?php echo $token->token ?></td>
            <td><?php echo $token->description ?></td>
            <td><?php echo printCapabilities($token->capabilities) ?></td>
            <td><?php echo printProperties($token->properties) ?></td>
            <td>
            <?php if (!isset($token->isAdmin) || $token->isAdmin !== true): ?>
                <button onclick="modifyToken(<?php echo $token->id ?>);">Modify</button> <button>Delete</button>
            <?php endif ?>
            </td>
        </tr>
<?php endforeach ?>
<?php endif ?>
    </tbody>
</table>
<?php endif ?>
<?php endif ?>
