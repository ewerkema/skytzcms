@extends('templates.admin.modals.modal', ['target'=>'sortMenuModal'])

@section('modal-header')
    <h4 class="modal-title"><strong>Menu indelen</strong></h4>
@overwrite

@section('modal-body')
    <p></p>
    @if (Page::getMenuWithSubpages()->count())
        <ul class="sortable">
            @foreach (Page::getMenuWithSubpages() as $page)
                <li class="menu-item" id="page_{{ $page->id }}" data-id="{{ $page->id }}">
                    <div>
                        <span class="glyphicon glyphicon-move"></span> {{ $page->title }}
                    </div>
                    @if (isset($page->subpages))
                        <ul>
                            @foreach ($page->subpages as $subpage)
                                <li class="menu-item" id="page_{{ $subpage->id }}" data-id="{{ $subpage->id }}">
                                    <div>
                                        <span class="glyphicon glyphicon-move"></span> {{ $subpage->title }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <p>Er staan geen pagina's in het menu.</p>
    @endif
@overwrite

@section('modal-footer')
    <button type="submit" @click="saveLayoutMenu" class="btn btn-primary">Opslaan</button>
@overwrite