<small>Tu pago será procesado con FIRST</small>
<input type="text" name="description" class="form-control"  value="Descripción cualquiera"/>
@error('description')
<div class="text-danger">{{ $message }}</div>
@enderror
