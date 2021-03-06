<!-- <script src="//cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.js"></script> -->
        <script type="text/javascript">
            $(document).ready(function() {
                $('#example').DataTable({
                    serverSide: true,
                    ajax: {
                        url: '/members/test',
                        method: 'POST'
                    },
                    columns: [
                        {data: "member_id", searchable: false},
                        {data: "code"},
                        {data: "firstname"},
                        {data: "lastname", searchable: false}
                    ]
                });
            });
        </script>

        <table id="example">
            <thead>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Balance</th>
            </thead>
            <tbody>
            </tbody>
        </table>


