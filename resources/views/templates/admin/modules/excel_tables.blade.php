<div id="html-block-{{ $id }}" class="html_block">
    @if ($excelTable = \App\Models\ExcelTable::find($id))
        {!! $excelTable->table !!}
    @else
        <p>Dit tabel bestaat niet meer / is verwijderd.</p>
    @endif
</div>
