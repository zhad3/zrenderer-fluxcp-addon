<?php if (!defined('FLUX_ROOT')) exit; ?>
<script>
function toggleToken(btn) {
    const input = btn.previousElementSibling;
    if (input) {
        if (input.type === "text") {
            btn.innerHTML = "[Show]";
            input.type = "password";
        } else {
            btn.innerHTML = "[Hide]";
            input.type = "text";
        }
    }
}
</script>
<h2>Zrenderer &mdash; Access tokens</h2>
<?php if (isset($_POST['create'])): ?>
<?php if ($error): ?>
<p class="notice">
    <strong>An error occurred:</strong> <?php echo $errorMessage ?>
</p>
<?php else: ?>
<p class="notice">
    <strong>Response:</strong> <?php echo $response ?>
</p>
<?php endif ?>
<?php endif ?>
<h3 id="jump-modify-form">Create token</h3>
<p>
    <form method="post" id="form__create-form" action="<?php echo $this->url('renderplayer', 'newtoken') ?>">
        <table class="vertical-table">
            <tbody>
                <tr>
                    <th col="row"><label for="form__description">Description</label></th>
                    <td colspan="3"><input type="text" id="form__description" name="description" style="width:100%"/ value="<?php echo getSubmittedValue("description") ?>"></td>
                </tr>
                <tr>
                    <th rowspan="2" col="row">Capabilities</th>
                    <td>
                        <label for="form__capabilities-readHealth">readHealth</label><input type="checkbox" id="form__capabilities-readHealth" name="capabilities-readHealth"<?php echo isCapabilityChecked("capabilities-readHealth") ?>/>
                    </td>
                    <td>
                        <label for="form__capabilities-readAccessTokens">readAccessTokens</label><input type="checkbox" id="form__capabilities-readAccessTokens" name="capabilities-readAccessTokens"<?php echo isCapabilityChecked("capabilities-readAccessTokens") ?>/>
                    </td>
                    <td>
                        <label for="form__capabilities-modifyAccessTokens">modifyAccessTokens</label><input type="checkbox" id="form__capabilities-modifyAccessTokens" name="capabilities-modifyAccessTokens"<?php echo isCapabilityChecked("capabilities-modifyAccessTokens") ?>/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="form__capabilities-revokeAccessTokens">revokeAccessTokens</label><input type="checkbox" id="form__capabilities-revokeAccessTokens" name="capabilities-revokeAccessTokens"<?php echo isCapabilityChecked("capabilities-revokeAccessTokens") ?>/>
                    </td>
                    <td colspan="2">
                        <label for="form__capabilities-createAccessTokens">createAccessTokens</label><input type="checkbox" id="form__capabilities-createAccessTokens" name="capabilities-createAccessTokens"<?php echo isCapabilityChecked("capabilities-createAccessTokens") ?>/>
                    </td>
                </tr>
                <tr>
                    <th rowspan="2" col="row">Properties</th>
                    <th col="row"><label for="form__properties-maxJobIdsPerRequest">maxJobIdsPerRequest</label></th>
                    <td colspan="2"><input type="text" id="form__properties-maxJobIdsPerRequest" name="properties-maxJobIdsPerRequest" style="width:50px" value="<?php echo getSubmittedValue("properties-maxJobIdsPerRequest") ?>"/></td>
                </tr>
                <tr>
                    <th col="row"><label for="form__properties-maxRequestsPerHour">maxRequestsPerHour</label></th>
                    <td colspan="2"><input type="text" id="form__properties-maxRequestsPerHour" name="properties-maxRequestsPerHour" style="width:50px" value="<?php echo getSubmittedValue("properties-maxRequestsPerHour") ?>"/></td>
                </tr>
                <tr>
                    <td colspan="4"><input type="submit" name="create" value="Submit"/></td>
                </tr>
            </tbody>
        </table>
    </form>
</p>
