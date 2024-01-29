<?php
ob_start();
?>

<div class="content w-75 m-auto p-5">
    <h1>Nouvel Article</h1>
    <div class="d-flex flex-column">
        <div class="d-flex gap-2">
            <div class="col">
                <div class="input-group mb-3">
                    <span class="input-group-text">Titre</span>
                    <input type="text" class="form-control" name="Title" id="Title">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Meta Title</span>
                    <input type="text" class="form-control" name="MetaTitle" id="MetaTitle">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Slug</span>
                    <input type="text" class="form-control" name="Slug" id="Slug">
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text">Etat publication</span>
                    <select class="form-select" name="published" id="published">
                        <option value=0>non publié</option>
                        <option value=1>publié</option>
                    </select>
                </div>
            </div>
            <div class="form-floating col">
                <textarea name="summary" class="form-control" placeholder="Extrait" id="summary" style="height: 200px;"></textarea>
                <label for="summary">Extrait</label>
            </div>
        </div>
        <div>
            <div id="toolbar" class="mb-3">
                <button class="btn btn-light fw-bold" onclick="execCmd('bold')">G</button>
                <button class="btn btn-light fw-lighter" onclick="execCmd('italic')">I</button>
                <button class="btn btn-light text-decoration-line-through" onclick="execCmd('underline')">S</button>
                <button class="btn btn-light" onclick="linkCMd()">Lien</button>
                <button class="btn btn-light" onclick="execCmd('delete')">supprimé</button>
                <button class="btn btn-light" onclick="execCmd('unlink')">unlink</button>
                <button class="btn btn-light" onclick="execCmd('selectAll')">Tout séléctionner</button>
                <!-- <ul>
        <li><button onclick="HeadingCmd('H1')">H1</button></li>
        <li><button onclick="HeadingCmd('h2')">H2</button></li>
        <li><button onclick="HeadingCmd('h3')">H3</button></li>
        <li><button onclick="HeadingCmd('h4')">H4</button></li>
    </ul> -->
            </div>
            <div class="form-floating mb-3">
                <iframe id="editor" name="editor" class="form-control" style="height: 600px;"></iframe>
                <label for="editor">Article</label>
            </div>
            <div class="d-flex gap-2">
                <button id="saveButton" class="btn btn-primary" onclick="saveContent()">Enregistrer</button>
                <div id="spinnerId" class="spinner-border" style="width: 3rem; height: 3rem; display:none;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>





</div>
<script>
    window.onload = function() {
        // Initialiser l'iframe comme éditable.
        var editor = document.getElementById('editor').contentWindow;
        editor.document.designMode = "on";

        execCmd = function(command) {
            editor.document.execCommand(command, false, null);
        }

        linkCMd = function() {
            let link = prompt('Entrer le lien:', 'http://')
            let newLink = editor.document.execCommand('createlink', false, link)
            newLink.target = "_blank";
        }

        /* HeadingCmd = function($h) {
            console.log($h)
            editor.document.execCommand("heading", false, $h);
        } */

    }

    function test() {
        let title = document.getElementById('Title').value
        let MetaTitle = document.getElementById('MetaTitle').value
        let Slug = document.getElementById('Slug').value
        let published = document.getElementById('published').value
        let summary = document.getElementById('summary').value

        console.log(title)
        console.log(MetaTitle)
        console.log(Slug)
        console.log(published)
        console.log(summary)
    }

    async function saveContent() {
        let saveButton = document.getElementById('saveButton')
        saveButton.setAttribute('disabled', '')
        let title = document.getElementById('Title').value;
        let MetaTitle = document.getElementById('MetaTitle').value;
        let Slug = document.getElementById('Slug').value;
        let published = document.getElementById('published').value;
        let summary = document.getElementById('summary').value;
        let content = document.getElementById('editor').contentWindow.document.body.innerHTML;


        document.getElementById('spinnerId').style.display = 'inline-block';

        try {
            const response = await fetch('/admin/testEditor', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'content=' + encodeURIComponent(content) +
                    '&Title=' + encodeURIComponent(title) +
                    '&MetaTitle=' + encodeURIComponent(MetaTitle) +
                    '&Slug=' + encodeURIComponent(Slug) +
                    '&published=' + encodeURIComponent(published) +
                    '&summary=' + encodeURIComponent(summary)
            });
            await delay(1000);
            const data = await response.text();
            console.log(data);
        } catch (error) {
            console.error('Error:', error);
        } finally {
            saveButton.removeAttribute('disabled')
            document.getElementById('spinnerId').style.display = 'none';
        }
    }

    function delay(time) {
        return new Promise(resolve => setTimeout(resolve, time));
    }
</script>

<?php

$content = ob_get_clean();

require_once('../src/views/admin/layout.php');
