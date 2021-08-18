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
    * Count not assigned cases which are active
    *
    *@return
    */
    public function getNotAssCasesSql()
    {
        $status = 'Active';
        $assigned = NULL;
        $sql = $this->conn->prepare("SELECT COUNT(*) FROM v_fmcm_caseinfo WHERE status = :status AND assigned IS NULL");
        $sql->execute([$status]);
        $res = $sql->fetchColumn();
        return $res;
    }

    /**
    * Count lodgged in users closed cases
    *
    *@return
    */
    public function getClosedUsrCasesSql()
    {
        $id = $this->getUname();
        $sql = $this->conn->prepare("SELECT COUNT(*) FROM v_fmcm_caseinfo WHERE closedby = :id");
        $sql->execute([$id]);
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
        $getNotAssCase = $this->getNotAssCasesSql();
        $getClosedUsrCases = $this->getClosedUsrCasesSql();
        $html = null;

        $html .= "
            <script>
                var ctx = document.getElementById('myChart');
                var myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['My cases', 'Active cases', 'Assigned to others', 'Closed cases', 'Not assigned', 'My closed cases'],
                        datasets: [{
                            label: 'Amount of Cases',
                            data: [{$myCases}, {$getAllActive}, $getOthersActive, $getAllClosed, $getNotAssCase, $getClosedUsrCases],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 146, 0, 0.33)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 146, 0, 1)'
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
