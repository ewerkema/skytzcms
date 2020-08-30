<template>
  <div>
    <div class="col-md-12">
      <p><i>Alle pagina's in het menu kunnen beheren? Klik dan rechtsboven op je profiel en vervolgens op "Menu Instellingen".</i></p>
      <div class="form-horizontal">
        <div class="form-group">
          <label for="page" class="col-md-3 control-label">Pagina</label>

          <div class="col-md-8">
            <select class="form-control" id="page" name="page" v-model="selectedPage">
              <option value="" disabled>Selecteer een pagina</option>
              <option v-for="page in pages" :value="page.id">
                {{ page.title }}{{ pageInMenu(page) ? " (reeds in menu aanwezig)" : "" }}
              </option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="parent_id" class="col-md-3 control-label">Weergeven in submenu van</label>

          <div class="col-md-8">
            <select class="form-control" id="parent_id" name="parent_id" v-model="selectedParentMenuItem">
              <option value="">Geen submenu</option>
              <option v-for="menuItem in rootMenuItems" :value="menuItem.id">
                {{ menuItem.title !== null ? menuItem.title : (findPage(menuItem.page_id) !== undefined ? findPage(menuItem.page_id).title : "") }}
              </option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="open_in_new_tab" class="col-md-3 control-label">Openen in nieuw tabblad</label>

          <div class="col-md-8">
            <label class="Switch">
              <input type="checkbox" name="open_in_new_tab" id="open_in_new_tab" v-model="openInNewTab">
              <div class="Switch__slider"></div>
            </label>
          </div>
        </div>

        <div class="form-group">
          <div class="col-md-8 col-md-offset-3">
            <button class="btn btn-success right" @click="addPageToMenu()" :disabled="selectedPage.length === 0">Toevoegen aan menu</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import AutoloadModal from "./AutoloadModal";

  export default {
    extends: AutoloadModal,

    props: {
      url: {
        type: String,
        default: "",
      }
    },

    created() {
      if (this.url.length === 0) {
        this.baseUrl = this.getBaseUrl() + '/';
      } else {
        this.baseUrl = this.url;
      }
    },

    data() {
      return {
        pages: [],
        menu: [],
        menuItems: [],
        selectedPage: "",
        selectedParentMenuItem: "",
        openInNewTab: false,
      };
    },

    computed: {
      menuPages() {
        return _.filter(this.pages, (page) => this.pageInMenu(page));
      },

      rootMenuItems() {
        return _.filter(this.menuItems, (menuItem) => menuItem.parent_id === null);
      }
    },

    methods: {
      loadFromDatabase() {
        $.get('/cms/menu', (data) => this.menu = data.menu);
        $.get('/cms/menu?list=true', (data) => this.menuItems = data.menu);

        return $.get('/cms/pages', (data) => this.pages = data);
      },

      pageInMenu(page) {
        return _.find(this.menuItems, ['page_id', page.id]);
      },

      findPage(pageId) {
        return _.find(this.pages, ['id', pageId]);
      },

      addPageToMenu: function() {
        let page = this.findPage(this.selectedPage);

        if (page === undefined) {
          swal({
            title: "Pagina niet gevonden",
            text: 'Pagina kon niet worden gevonden',
            type: "warning",
            timer: 2000
          }).done();
          return;
        }

        this.addMenuItem();
      },

      addMenuItem() {
        let data = {
          page_id: this.selectedPage,
          open_in_new_tab: this.openInNewTab ? 1 : 0,
          parent_id: this.selectedParentMenuItem,
        };

        $.post('/cms/menu', data)
          .then(() => {
            swal({
              title: "Pagina toegevoegd!",
              text: 'Pagina is succesvol toegevoegd.',
              type: "success",
              timer: 2000
            }).done();

            $(this.target).modal('hide');
          })
          .fail(function(xhr, status, error) {
            swal({
              title: "Verzoek mislukt",
              text: 'Kon pagina niet toevoegen aan menu: \n' + _.flatMap(JSON.parse(xhr.responseText), (error) => "- " + error + "\n"),
              type: "error",
            })
          });
      },
    }
  }
</script>