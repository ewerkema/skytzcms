<template>
  <div>
    <div class="col-md-12">
      <p><i>Alle links in het menu kunnen beheren? Klik dan rechtsboven op je profiel en vervolgens op "Menu Instellingen".</i></p>
      <div class="form-horizontal">
        <div class="form-group">
          <label for="link" class="col-md-3 control-label">Losse link</label>

          <div class="col-md-8">
            <input type="text" id="link" name="link" v-model="link" class="form-control" placeholder="URL" />
          </div>
        </div>

        <div class="form-group">
          <label for="title" class="col-md-3 control-label">Link naam</label>

          <div class="col-md-8">
            <input type="text" id="title" name="title" v-model="title" class="form-control" placeholder="Link naam..." />
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
            <button class="btn btn-success right" @click="addLinkToMenu()" :disabled="link.length === 0 || title.length === 0">Toevoegen aan menu</button>
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
        link: "",
        title: "",
        selectedParentMenuItem: "",
        openInNewTab: false,
      };
    },

    computed: {
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

      findPage(pageId) {
        return _.find(this.pages, ['id', pageId]);
      },

      addLinkToMenu: function() {
        if (this.link.length === 0 || this.title.length === 0) {
          swal({
            title: "Gegevens ontbreken",
            text: 'Titel en link moeten opgegeven worden!',
            type: "warning",
            timer: 2000
          }).done();
          return;
        }

        this.addMenuItem();
      },

      addMenuItem() {
        let data = {
          link: this.link,
          title: this.title,
          open_in_new_tab: this.openInNewTab ? 1 : 0,
          parent_id: this.selectedParentMenuItem,
        };

        $.post('/cms/menu', data)
          .then(() => {
            swal({
              title: "Link toegevoegd!",
              text: 'Losse link is succesvol toegevoegd.',
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