import 'https://cdn.jsdelivr.net/npm/@fortawesome/fa-icon-chooser@0.10.0-2/dist/fa-icon-chooser/fa-icon-chooser.esm.js';

(function ($) {
  // Font Awesome API endpoint for GraphQL
  const API_URL = 'https://api.fontawesome.com';

  let iconSets = [];
  let currentField = null;
  let container = null;

  function getUrlText(url) {
    return fetch(url).then(response => {
      if (response.ok) {
        return response.text();
      } else {
        throw new Error('Bad query for url: ' + url);
      }
    });
  }

  async function handleQuery(query, variables) {

    const cleanedQuery = query.replace(/\s+/g, ' ');

    const formData = new FormData();
    formData.append('action', 'acffa_fa_query');
    formData.append('query', cleanedQuery);
    formData.append('variables', JSON.stringify(variables || {}));
    formData.append('nonce', currentField.find('input[name="acffa_nonce"]').val());

    const res = await fetch(ACFFA.ajax_url, {
      method: 'POST',
      body: formData,
    });

    if (!res.ok) {
      throw new Error('Font Awesome API request failed');
    }

    const data = await res.json();
    if (data.errors) throw new Error('Font Awesome API returned errors');
    return data.data;
  }

  function includeFamilyStyle(familyStyle) {
    let familyStyleString = familyStyle['family'] + '_' + familyStyle['style'];
    if (familyStyle['style'] === 'brands') {
      familyStyleString = 'brands';
    }

    return iconSets.includes(familyStyleString);
  }

  function handleResult(event) {
    const result = event.detail || {};

    let iconData = {
      family: result.family || '',
      style: result.style || '',
      id: result.iconName || '',
      unicode: result.icon[3] || '',
    };

    currentField.find('.acf-input-wrap input').val(`${JSON.stringify(iconData)}`);

    const preview = currentField.find('.icon_preview');
    preview.html('');
    if (iconData.style && iconData.id) {
      const i = document.createElement('i');
      let classes = [];
      classes.push('fa-' + iconData.family);
      classes.push('fa-' + iconData.style);
      classes.push('fa-' + iconData.id);
      i.className = classes.join(' ');
      preview.append(i);
    }

    // Remove chooser when done (optional)
    const chooser = container.find('fa-icon-chooser');
    if (chooser.length) chooser.remove();

    container.removeClass('open');
  }

  function insertIconChooserContainer() {
    const containerHtml = `
		<div id="acffa-icon-chooser-container">
        <div class="acffa-icon-chooser-card">
			<div class="title-wrapper">
				<h2>Choose an Icon</h2>
				<button type="button" class="acffa-icon-chooser-close button-link"><?php esc_html_e( 'Close', 'acf-font-awesome' ); ?></button>
			</div>
			<div class="chooser-wrapper"></div>
        </div>
		</div>
    `;
    $('body').append(containerHtml);

    container = $('#acffa-icon-chooser-container');
  }
  insertIconChooserContainer();

  // when clicking outside the card, close the chooser
  container.on('click', function (e) {
    if ($(e.target).is('#acffa-icon-chooser-container')) {
      container.removeClass('open');
      container.find('fa-icon-chooser').remove();
    }
  });

  function setupFontAwesomeFieldActions($el) {
    $el.find('.fa-icon-chooser-open').on('click', function () {
        console.log('Icon chooser open clicked');
        let wrapper = $(this).closest('.acf-field');
        currentField = wrapper;
        let iconSetsInput = wrapper.find('input[name="icon_sets"]');

        // Remove existing chooser
        let existing = container.find('fa-icon-chooser');
        if (existing.length) existing.remove();

        const el = document.createElement('fa-icon-chooser');

        // Attach required callbacks as properties
        el.handleQuery = handleQuery;
        el.getUrlText = getUrlText;
        el.includeFamilyStyle = includeFamilyStyle;
        // Configure your chooser – version or kit-token, etc.
        // el.setAttribute('version', '7.1.0'); // example; adjust for your setup
        el.setAttribute('kit-token', ACFFA.kit_token || '');

        iconSets = iconSetsInput.val() ? iconSetsInput.val().split(',') : [];

        el.addEventListener('finish', handleResult);

        // create empty slot start-view-heading and start-view-detail
        const startViewHeading = document.createElement('div');
        startViewHeading.setAttribute('slot', 'start-view-heading');
        startViewHeading.textContent = '';
        el.appendChild(startViewHeading);
        const startViewDetails = document.createElement('div');
        startViewDetails.setAttribute('slot', 'start-view-detail');
        startViewDetails.textContent = '';
        el.appendChild(startViewDetails);

        // Optional slot content
        const fatalErrorHeading = document.createElement('p');
        fatalErrorHeading.setAttribute('slot', 'fatal-error-heading');
        fatalErrorHeading.textContent = 'Something went wrong';
        el.appendChild(fatalErrorHeading);

        container.find('.chooser-wrapper').append(el);

        container.addClass('open');
      });
    }

  acf.add_action(
    'ready_field/type=font-awesome append_field/type=font-awesome show_field/type=font-awesome',
    function ($el) {
      console.log('Font Awesome field ready/append/show action triggered');
        setupFontAwesomeFieldActions($el);      
    }
  );

  acf.add_action( 'open_field/type=font-awesome change_field_type/type=font-awesome', function( $el ) {
    console.log('Font Awesome field open/change action triggered');
    setupFontAwesomeFieldActions($el);
    });
})(jQuery);
