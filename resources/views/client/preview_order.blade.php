@if(count($rows) > 0)
<table class="table preview_order">
    <thead>
      <tr>
        <th class="pad4">CLAVE COMPRA</th>
        <th class="pad4">PARTIDA</th>
        <th class="pad4">FECHA ELABORACION</th>
        <th class="pad4">FECHAENVIO</th>
        <th class="pad4">FECHARECEPCION</th>
        <th class="pad4">MONEDA</th>
        <th class="pad4">TIPOCAMBIO</th>
        <th class="pad4">CANTIDAD</th>
        <th class="pad4">MONTO PARTIDA</th>
        <th class="pad4">FECHAELABORACION</th>
        <th class="pad4">FECHARECEPCION</th>
        <th class="pad4">CLAVEPRODUCTO</th>
        <th class="pad4">CLAVE PRODUCTO PROV</th>
        <th class="pad4">NOMBRE</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rows as $row)
        <tr>
          <td>{{ strtoupper($row->clave_compra) }}</td>
          <td>{{ strtoupper($row->partida) }}</td>
          <td>{{ strtoupper($row->fecha_elaboracion) }}</td>
          <td>{{ strtoupper($row->fechaenvio) }}</td>
          <td>{{ strtoupper($row->fecharecepcion) }}</td>
          <td>{{ strtoupper($row->moneda) }}</td>
          <td>{{ strtoupper($row->tipocambio) }}</td>
          <td>{{ strtoupper($row->cantidad) }}</td>
          <td>{{ strtoupper($row->monto_partida) }}</td>
          <td>{{ strtoupper($row->fechaelaboracion) }}</td>
          <td>{{ strtoupper($row->fecharecepcion) }}</td>
          <td>{{ strtoupper($row->claveproducto) }}</td>
          <td>{{ strtoupper($row->clave_producto_prov) }}</td>
          <td>{{ strtoupper($row->nombre) }}</td>
        </tr>
      @endforeach
    </tbody>
</table>
@endif
