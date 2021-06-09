/**
 * @file en.js
 * @description Add english text strings as variables for internationalization (i18n)
 * @param %v - Add this where you expect variable (dynamic) content.
 * This will allow the translations to put it in the correct location
 * @param %p - Add this where you want to add pluralization.
 * You'll need to provide the logic for this when the string is used.
 */

// NOTE: `String.raw` is used for entries with translation tokens (%v,%p) in the slight chance
// we/translators want to escape those exact strings visually in the dom (ex: \%v).
// Adding this makes it so you don't have to double-escape due to default DOM behavior
// (which a translator might not know about - single \ in normal strings are removed).
// Example: Allows: String.raw`Escaping \%v` instead of having to do: String.raw`Escaping \\%v`

var es = {
  lang: 'es',
  columntypes: {
    editor: 'Editar',
    remover: 'Eliminar',
    selector: 'Seleccionar',
  },
  context: {
    copyCell: 'Copiar Celda',
    pasteCell: 'Pegar Celda',
    insertRecordLabel: 'Añadir Registro Nuevo',
    insertRecordHere: 'Aqui',
    insertRecordEnd: 'Al Final',
    insertRecordStart: 'Al Principio',
    deleteRow: 'Eliminar Fila',
    sortColumn: 'Ordenar Columna',
    editCell: 'Editar Celda',
    editRow: 'Editar Fila',
    selectCell: 'Seleccionar Celda',
    selectRow: 'Seleccionar Fila',
    deselect: 'Deseleccionar',
    previousPage: 'Regresar a la página anterior',
    nextPage: 'Ir a la página siguiente',
    firstPage: 'Regresar a la primera página',
    lastPage: 'Ir a la página final',
    version: 'Sobre ZingGrid',
    save: 'Guardar',
    cancel: 'Cancelar',
  },
  date: {
    shortMonth: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    longMonth: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    twoWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
    shortWeek: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
    longWeek: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sábado'],
    am: 'am',
    pm: 'pm',
    startWeek: 0,
    startYear: 6,
  },
  dialog: {
    cancel: 'Cancelar',
    close: 'X',
    confirm: 'Confirmar',
    create: 'Crear',
    delete: 'Eliminar',
    ok: 'Ok',
    recordCreate: {
      label: 'Crear un nuevo registro',
      successMsg: '¡Su registro fue creado!',
      errorMsg: 'El servidor encontró un error y no se pudo crear el registro.',
    },
    recordDelete: {
      body: '¿Seguro que quiere eliminar este registro?',
      label: 'Confirmar eliminación',
      successMsg: '¡Su registro fue eliminado!',
      errorMsg: 'El servidor encontró un error y no se pudo eliminar el registro.',
    },
    recordInfo: {
      label: 'Información de Registro',
    },
    recordUpdate: {
      label: 'Actualizar este registro',
      successMsg: '¡Su registro fue actualizado!',
      errorMsg: 'El servidor encontró un error y no se pudo actualizar el registro.',
    },
    fieldUpdate: {
      label: 'Actualizar este campo',
      successMsg: '¡Su campo fue actualizado!',
      errorMsg: 'El servidor encontró un error y no se pudo actualizar el campo.',

    },
    removeXSelectedRows: {
      body: '¿Seguro que quiere eliminar estos registros?',
      label: 'Eliminar %v registro%p',
      successMsg: '¡Sus registro%p fueron eliminados!',
    },
    version: {
      body: 'creado en',
      label: 'Versión Actual',
    },
    save: 'Guardar',
  },
  iconSet: {
    invalidVendor: 'El valor agregado a [%v] no coincide con la lista de proveedores permitidos.',
    invalidVendorMethod: 'Error Interno: Se produjo un problema al cargar los íconos [%v = "%v"]. Volviendo a los íconos predeterminados.',
  },
  loadMask: {
    title: 'Cargando...',
  },
  pagination: {
    page: 'Página',
    pageOf: 'de',
    rows: 'Filas',
  },
  tools: {
    filter: 'Filtrar',
    search: 'Buscar',
    selected: '%v Elemento%p Seleccionado',
  },
  tooltip: {
    layoutCard: 'Diseño de Tarjeta',
    layoutRow: 'Diseño de Fila',
    recordCreate: 'Añadir Registro',
    reload: 'Recargar la Cuadrícula',
    editrecord: 'Editar Registro',
    firstpage: 'Ir a la Primera Página',
    fixedmenu: 'Abre el Menú',
    lastpage: 'Ir a la Última Página',
    menu: 'Abre el Menú de la Columna',
    nextpage: 'Ir a la Siguente Página',
    prevpage: 'Ir a la Página Anterior',
    removerecord: 'Elminiar Registro',
    search: 'Abre la Búsqueda',
  },
  watermark: {
    poweredBy: '',
  },
};
