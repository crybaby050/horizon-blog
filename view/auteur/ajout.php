<!-- ════════ AJOUT ARTICLE ════════ -->
<div class="au-page">

  <div class="au-page-header fade-up">
    <div>
      <h1 class="au-page-title">Nouvel article</h1>
      <p class="au-page-sub">Tous les champs sont obligatoires.</p>
    </div>
    <a href="<?= path('auteur','articles') ?>" class="au-btn-ghost">
      <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="14" height="14">
        <path d="M15 18l-6-6 6-6"/>
      </svg>
      Retour
    </a>
  </div>

  <?php if (!empty($errors['global'])): ?>
    <div class="au-alert au-alert-err fade-up">
      <?= htmlspecialchars($errors['global']) ?>
    </div>
  <?php endif; ?>

  <form method="POST"
        action="<?= path('auteur','ajout') ?>"
        enctype="multipart/form-data"
        class="au-form fade-up"
        id="articleForm"
        novalidate>

    <input type="hidden" name="controller"   value="auteur"/>
    <input type="hidden" name="action"       value="ajout"/>
    <input type="hidden" name="post_action"  value="add_article"/>

    <div class="au-form-grid">

      <!-- ── COLONNE PRINCIPALE ── -->
      <div class="au-form-main">

        <!-- Titre -->
        <div class="au-field <?= !empty($errors['titre']) ? 'au-field-err' : '' ?>">
          <label for="af-titre">
            Titre de l'article <span class="au-req">*</span>
          </label>
          <input type="text" id="af-titre" name="titre"
                 value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>"
                 placeholder="Un titre accrocheur…"
                 maxlength="255"
                 required/>
          <?php if (!empty($errors['titre'])): ?>
            <span class="au-field-msg"><?= $errors['titre'] ?></span>
          <?php else: ?>
            <span class="au-field-hint">Entre 5 et 255 caractères.</span>
          <?php endif; ?>
          <div class="au-char-bar"><span id="titreCount">0</span>/255</div>
        </div>

        <!-- Description -->
        <div class="au-field <?= !empty($errors['description']) ? 'au-field-err' : '' ?>">
          <label for="af-desc">
            Description / Sous-titre <span class="au-req">*</span>
          </label>
          <textarea id="af-desc" name="description" rows="3"
                    placeholder="Un résumé de l'article visible dans les listes…"
                    required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
          <?php if (!empty($errors['description'])): ?>
            <span class="au-field-msg"><?= $errors['description'] ?></span>
          <?php else: ?>
            <span class="au-field-hint">Minimum 10 caractères.</span>
          <?php endif; ?>
        </div>

        <!-- Contenu -->
        <div class="au-field <?= !empty($errors['contenu']) ? 'au-field-err' : '' ?>">
          <label for="af-contenu">
            Contenu <span class="au-req">*</span>
          </label>
          <textarea id="af-contenu" name="contenu" rows="18"
                    class="au-contenu-area"
                    placeholder="Rédigez votre article ici…"
                    required><?= htmlspecialchars($_POST['contenu'] ?? '') ?></textarea>
          <?php if (!empty($errors['contenu'])): ?>
            <span class="au-field-msg"><?= $errors['contenu'] ?></span>
          <?php else: ?>
            <span class="au-field-hint">Minimum 20 caractères.</span>
          <?php endif; ?>
          <div class="au-char-bar"><span id="contenuCount">0</span> caractères</div>
        </div>

      </div>

      <!-- ── SIDEBAR FORMULAIRE ── -->
      <div class="au-form-sidebar">

        <!-- Images -->
        <div class="au-form-card <?= !empty($errors['images']) ? 'au-field-err' : '' ?>">
          <div class="au-form-card-title">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="16" height="16">
              <rect x="3" y="3" width="18" height="18" rx="2"/>
              <circle cx="8.5" cy="8.5" r="1.5"/>
              <polyline points="21 15 16 10 5 21"/>
            </svg>
            Images de l'article
          </div>

          <div class="au-upload-zone" id="uploadZone"
               onclick="document.getElementById('af-images').click()"
               ondragover="event.preventDefault();this.classList.add('dragging')"
               ondragleave="this.classList.remove('dragging')"
               ondrop="handleDrop(event)">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" width="36" height="36" stroke="#9ca3af">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
              <polyline points="17 8 12 3 7 8"/>
              <line x1="12" y1="3" x2="12" y2="15"/>
            </svg>
            <p>Glissez vos images ici<br/><span>ou cliquez pour sélectionner</span></p>
            <div class="au-upload-formats">JPG, PNG, WEBP, GIF — max 5 Mo/image</div>
          </div>
          <input type="file" id="af-images" name="images[]"
                 accept="image/*" multiple style="display:none"
                 onchange="previewImages(this)"/>

          <?php if (!empty($errors['images'])): ?>
            <span class="au-field-msg"><?= $errors['images'] ?></span>
          <?php endif; ?>

          <div class="au-preview-grid" id="previewGrid"></div>
        </div>

        <!-- Catégories -->
        <div class="au-form-card <?= !empty($errors['categories']) ? 'au-field-err' : '' ?>">
          <div class="au-form-card-title">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" width="16" height="16">
              <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
              <line x1="7" y1="7" x2="7.01" y2="7"/>
            </svg>
            Catégories <span class="au-req">*</span>
          </div>
          <p class="au-form-card-hint">Choisissez 1 à 5 catégories.</p>

          <div class="au-categ-list">
            <?php foreach ($categories as $cat): ?>
              <?php $checked = in_array($cat['id'], (array)($_POST['categories'] ?? [])); ?>
              <label class="au-categ-item <?= $checked ? 'checked' : '' ?>">
                <input type="checkbox" name="categories[]"
                       value="<?= $cat['id'] ?>"
                       <?= $checked ? 'checked' : '' ?>
                       onchange="toggleCateg(this)"/>
                <span class="au-categ-check">
                  <svg fill="none" viewBox="0 0 24 24" stroke-width="3" stroke-linecap="round" width="10" height="10">
                    <polyline points="20 6 9 17 4 12"/>
                  </svg>
                </span>
                <?= htmlspecialchars($cat['libelle']) ?>
              </label>
            <?php endforeach; ?>
          </div>
          <?php if (!empty($errors['categories'])): ?>
            <span class="au-field-msg"><?= $errors['categories'] ?></span>
          <?php endif; ?>
        </div>

        <!-- Boutons -->
        <div class="au-form-card au-form-submit-card">
          <button type="submit" class="au-btn-primary au-btn-full" id="submitBtn">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" width="15" height="15">
              <line x1="22" y1="2" x2="11" y2="13"/>
              <polygon points="22 2 15 22 11 13 2 9 22 2"/>
            </svg>
            Publier l'article
          </button>
          <p class="au-form-note">
            Votre article sera soumis à validation avant d'être publié.
          </p>
        </div>

      </div>
    </div>
  </form>
</div>