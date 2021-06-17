<?php
/***
* This is the class for graphs in dashboard
*
*/

Class Graphs
{
    private $conn;

    public function __construct($pdo)
    {
        $this->conn = $pdo;
    }
    /**
    * Get logged in users username
    *
    * @return
    */

    public function getUname()
    {
        $uname = $_SESSION['uname'] ?? null;
        return $uname;
    }

    /**
    * Count amount of active cases from db and display it in graph
    *
    *@return
    */
    public function getAllActiveCasesSql()
    {
        // $id = $this->getUname();
        $status = 'Active';
        $sql = $this->conn->prepare("SELECT COUNT(*) FROM v_fmcm_caseinfo WHERE status = :status");
        $sql->execute([$status]);
        $res = $sql->fetchColumn();
        return $res;
    }

    /**
    * Count amount of assigned to logged in user cases from db and display it in graph
    *
    *@return
    */
    public function getMyCasesSql()
    {
        $id = $this->getUname();
        $status = 'Active';
        $sql = $this->conn->prepare("SELECT COUNT(assigned) FROM v_fmcm_caseinfo WHERE assigned = :id AND status = :status");
        $sql->execute([$id, $status]);
        $res = $sql->fetchColumn();
        return $res;
    }

    /**
    * Count active cases assigned to others
    *
    *@return
    */
    public function getOthersCasesSql()
    {
        $id = $this->getUname();
        $status = 'Active';
        $sql = $this->conn->prepare("SELECT COUNT(*) FROM v_fmcm_caseinfo WHERE status = :status AND assigned <> :id");
        $sql->execute([$status, $id]);
        $res = $sql->fetchColumn();
        return $res;
    }

    /**
    * Count closed cases
    *
    *@return
    */
    public function getClosedCasesSql()
    {
        $id = $this->getUname();
        $status = 'Closed';
        $sql = $this->conn->prepare("SELECT COUNT(*) FROM v_fmcm_caseinfo WHERE status = :status");
        $sql->execute([$status]);
        $res = $sql->fetchColumn();
        return $res;
    }

    /**
    * Select case data from db and display it in graph
    *
    *@return
    */
    public function getAmountCases()
    {
        $myCases = $this->getMyCasesSql();
        $getAllActive = $this->getAllActiveCasesSql();
        $getOthersActive = $this->getOthersCasesSql();
        $getAllClosed = $this->getClosedCasesSql();

        $html = null;

        $html .= "
            <script>
                var ctx = document.getElementById('myChart');
                var myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['My cases', 'All active', 'Assigned to others', 'All Closed'],
                        datasets: [{
                            label: 'Amount of Cases',
                            data: [{$myCases}, {$getAllActive}, $getOthersActive, $getAllClosed],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },

                });
            </script>
        ";
        return $html;
    }

}
