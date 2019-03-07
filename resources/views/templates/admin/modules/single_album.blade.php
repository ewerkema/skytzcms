<div class="portlet">
    <h1>{{ $album->name }}</h1>
    @include('templates.admin.modules.albums', ['id' => $album->id])
    <button href="#" onclick="window.history.back();">Ga terug</button>
</div>