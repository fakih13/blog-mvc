<?php
ob_start();
?>

<div class="content w-75 m-auto p-5">
    <h1>Nouvel Article</h1>
    <div class="container d-flex flex-column">
        <div class="row mb-3">
            <div class="col w-50">
                <div class="input-group mb-3">
                    <span class="input-group-text">Titre</span>
                    <input type="text" class="form-control" name="Title" id="Title">
                    <span id="Title-error"></span>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Meta Title</span>
                    <input type="text" class="form-control" name="MetaTitle" id="MetaTitle">
                    <span id="MetaTitle-error"></span>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Slug</span>
                    <input type="text" class="form-control" name="Slug" id="Slug">
                    <span id="Slug-error"></span>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text">Etat publication</span>
                    <select class="form-select" name="published" id="published">
                        <option value=0>non publié</option>
                        <option value=1>publié</option>
                    </select>
                    <span id="published-error"></span>
                </div>
                <div class="input-group w-100">
                    <span class="input-group-text">Tag</span>
                    <input type="text" name="input-tag" id="input-tag" class="form-control" list="tagsOptions">
                    <datalist id="tagsOptions"><!-- 
                        <option value="mer" data-value="1">
                        <option value="pêche" data-value="2">
                        <option value="recette poisson" data-value="3">
                        <option value="plats comorien" data-value="4"> -->
                    </datalist>

                    <!-- style="border: 0px; outline: 0px; max-width: 100%;" -->
                </div>
                <div id="tags" class="mw-50">
                </div>
            </div>
            <div class="col">
                <div class="form-floating">
                    <textarea name="summary" class="form-control" placeholder="Extrait" id="summary" style="height: 200px;"></textarea>
                    <label for="summary">Extrait</label>
                    <span id="summary-error"></span>
                </div>
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
                <iframe id="article" name="editor" class="form-control" style="height: 600px;"></iframe>
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
        var editor = document.getElementById('article').contentWindow;
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
        let saveButton = document.getElementById('saveButton');
        saveButton.setAttribute('disabled', '');
        let title = document.getElementById('Title').value;
        let MetaTitle = document.getElementById('MetaTitle').value;
        let Slug = document.getElementById('Slug').value;
        let published = document.getElementById('published').value;
        let summary = document.getElementById('summary').value;
        let content = document.getElementById('article').contentWindow.document.body.innerHTML;

        // Nettoyer les erreurs précédentes
        const fields = ['Title', 'MetaTitle', 'Slug', 'published', 'summary', 'editor', 'article'];
        fields.forEach(field => {
            const element = document.getElementById(field);
            if (element) {
                element.classList.remove('is-invalid');
                // Supprimer les éléments de feedback d'erreur précédents
                const feedback = element.parentNode.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.remove();
                }
            }
        });

        document.getElementById('spinnerId').style.display = 'inline-block';

        try {
            const response = await fetch('/admin/testEditor', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'article=' + encodeURIComponent(content) +
                    '&Title=' + encodeURIComponent(title) +
                    '&MetaTitle=' + encodeURIComponent(MetaTitle) +
                    '&Slug=' + encodeURIComponent(Slug) +
                    '&published=' + encodeURIComponent(published) +
                    '&summary=' + encodeURIComponent(summary)
            });

            await delay(1000);

            if (!response.ok) {
                const errorData = await response.json();
                throw {
                    status: response.status,
                    data: errorData
                };
            }

            const data = await response.json();
            console.log('Success:', data);
            // Gérer la réussite ici
        } catch (error) {
            if (error.data && error.data.errors) {
                for (const [field, message] of Object.entries(error.data.errors)) {
                    const errorElement = document.getElementById(field);
                    if (errorElement) {
                        errorElement.classList.add('is-invalid');
                        let feedBack = document.createElement('div');
                        feedBack.setAttribute('class', 'invalid-feedback');
                        feedBack.textContent = message;
                        errorElement.parentNode.appendChild(feedBack);
                    }
                }
            } else {
                console.error('Error:', error);
            }
        } finally {
            saveButton.removeAttribute('disabled');
            document.getElementById('spinnerId').style.display = 'none';
        }
    }

    function delay(time) {
        return new Promise(resolve => setTimeout(resolve, time));
    }

    const tags = document.getElementById('tags');
    const input = document.getElementById('input-tag');
    const dataList = document.getElementById('tagsOptions');
    console.log(dataList.options) // Assurez-vous que cet ID correspond à votre datalist

    function findMaxDataValue() {
        let maxValue = 0;
        // Parcourir toutes les options du datalist pour trouver la valeur maximale de data-value
        if (dataList) {
            for (let i = 0; i < dataList.options.length; i++) {
                const optionDataValue = parseInt(dataList.options[i].getAttribute('data-value'), 10) || 0;
                if (optionDataValue > maxValue) {
                    maxValue = optionDataValue;
                }
            }
        }
        return maxValue;
    }

    input.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();

            const tagContent = input.value.trim();
            if (tagContent !== '') {
                const tag = document.createElement('span');
                tag.innerText = tagContent;
                tag.innerHTML += '<button class="btn btn-danger delete-button">X</button>';
                tag.classList.add('p-1', 'd-flex', 'justify-center', 'gap-2', 'align-items-center');

                // Trouver la valeur maximale de data-value dans le datalist
                const maxValue = findMaxDataValue();

                // Rechercher si l'input correspond à une option dans le datalist
                const matchedOption = Array.from(dataList.options).find(option => option.value === tagContent);
                console.log(matchedOption)
                const newDataValue = matchedOption ? parseInt(matchedOption.getAttribute('data-value'), 10) : maxValue + 1;

                // Ajout de l'attribut data-value au span
                tag.setAttribute('data-value', newDataValue);

                tags.appendChild(tag);
                input.value = '';
            }
        }
    });

    tags.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-button')) {
            event.target.parentNode.remove();
        }
    });
</script>

<?php

$content = ob_get_clean();

require_once('../src/views/admin/layout.php');
