    <h1 class="adminContent_header">Create a new article</h1>
    <div id="admin_posts" class="admin_box">
        <form id="newArticle" method="POST" action="<?php echo BASEDIR.'staff/posts/submit';?>">
        <?php form_saveToken("newArticle"); ?>
            <input type="hidden" name = "newArticle" value = 'newArticle'/> 
            <h3>Who is working on this project?</h3>
            <table>
                <tr><th>Name</th><th>Due Date</th></tr>
                <tr><td><input type="text" name ="writer" placeholder="Writer" class="form_input form_text_input"/></td><td><input type="date" name="writerDueDate"/></td></tr>
                <tr><td> <input type="text" name ="photographer" placeholder="Photographer" class="form_input form_text_input"/></td><td><input type="date" name="photographerDueDate"/></td></tr>
                <tr><td><input type="text" name ="firstEditor" placeholder="firstEditor" class="form_input form_text_input"/></td><td><input type="date" name="firstEditorDueDate"/></td></tr>
                <tr><td><input type="text" name ="secondEditor" placeholder="secondEditor" class="form_input form_text_input"/></td><td><input type="date" name="secondEditorDueDate"/></td></tr>
                <tr><td><input type="text" name ="layoutEditor" placeholder="layoutEditor" class="form_input form_text_input"/></td><td><input type="date" name="layoutEditorDueDate"/></td></tr>
            </table>
        <input type="checkbox" name="notify">Notify these people via email?<br> <hr>
         Give your article a title:  <input type="text" name ="title" placeholder="Title" class="form_input form_text_input"/><br>
         Description (200 words max):  <br><textarea type="text" name ="description" placeholder="Title" class="form_input form_text_input"/></textarea><br>
         Body of article:<br><textarea name ="text" placeholder="" class="form_input form_password_input"/></textarea><br>
        <hr>
                <input type="file" name="attachment"/>  <br><br>
        <input type="submit" name ="submit" value="Submit" class="form_input form_submit_input"/>
        </form>
       
    </div>