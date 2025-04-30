<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.min.js"></script>
<script>
  $(document).ready(function() {
    $('.table').each(function() {
        if ($(this).find('tbody tr').length > 1) { // Ensure the table has at least one row
            $(this).DataTable({
                "paging": true,
                "searching": true,
                "order": [],
                "info": true,
                "lengthMenu": [10, 25, 50, 100],
                "autoWidth": false,
                "language": {
                    "zeroRecords": "No records available",
                    "emptyTable": "No data available in table"
                }
            });
        }
    });
});
</script>