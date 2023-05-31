/**
     * get lottery games
     * @param $lt_id int lottery id
     * @return array
     */
    public function getsubgames($lt_id)
    {
        $query = new query();

        $sql = "SELECT
        gn.gn_id AS game_id,
        gn.name AS name,
        gn.state AS status,
        gn.group_type AS group_type,
        gg.name AS game_group,
        og.odds_group_id AS subgame_id,
        og.label AS label,
        IF(og.guide IS NOT NULL, og.guide,gn.guide) AS guide,
        IF(og.odds IS NOT NULL, TRUNCATE(og.odds - TRUNCATE(((og.profit) * og.odds) / 100, 3), 3),TRUNCATE(gn.odds - TRUNCATE(((gn.profit) * gn.odds) / 100, 3), 3)) AS currentodds,
        gn.sample AS sample,
        gn.row AS `rows`,
        gn.start AS start,
        gn.end AS end,
        gn.columnSelection AS columnSelection,
        gn.rowSelection AS rowSelection,
        gn.showRowName AS showRowName,
        gn.startingPoint AS startingPoint,
        gn.endingPoint AS endingPoint,
        gn.model AS model
      FROM
        game_name gn
      JOIN
        game_group gg ON gn.game_group = gg.gp_id
      LEFT JOIN
        odds_group og ON gn.gn_id = og.game_play_id
      WHERE
        gg.game_type = ? AND gn.state = ?
      
      ";

        $res = $query->run($sql, [$lt_id, 'active']);
        // print_r($res);die;
        // echo json_encode($res);
        // die;
        // print_r($res);die;
        $result = [];
        $groupedData = [];
        $subgames = ["Dragon/Tiger/Tie","Stud", "Three cards", "Bull Bull"];
        $firstIndex = 0;
        foreach ($res as $item) {
            $game = [
                "game_id" => $item['game_id'],
                "name" => $item['name'],
                "status" => $item['status'],
                "subgame_id"=> $item['subgame_id']??0,
                "label" => $item['label'],
                "currentodds" => $item['currentodds'],
                "guide" => $item['guide'],
                "sample" => $item['sample'],
                "rows" => $item['rows'],
                "start" => $item['start'],
                "end" => $item['end'],
                "columnSelection" => $item['columnSelection'],
                "rowSelection" => $item['rowSelection'],
                "showRowName" => $item['showRowName'],
                "startingPoint" => $item['startingPoint'],
                "endingPoint" => $item['endingPoint'],
                "model" => $item['model']
            ];

            $groupType = $item['group_type'];
            $gameGroup = $item['game_group'];

            if (!isset($groupedData[$gameGroup])) {
                $groupedData[$gameGroup] = [
                    "group" => $gameGroup,
                    "subgroup" => []
                ];
            }

            $subgroupIndex = array_search($groupType, array_column($groupedData[$gameGroup]['subgroup'], 'name'));
            $formatDifferent = in_array($gameGroup, $subgames);
            if ($subgroupIndex === false && !$formatDifferent) {
                $groupedData[$gameGroup]['subgroup'][] = [
                    "name" => $groupType,
                    "games" => [$game]
                ];
            } else {
               
                
                // if (in_array($gameGroup, $formatDifferent) && $firstIndex>0) {
                //     $groupedData[$gameGroup]['subgroup'][$subgroupIndex]['games'][0]['subgames'][] = $game;
                // }else{
                //     $groupedData[$gameGroup]['subgroup'][$subgroupIndex]['games'][] = $game;
                //     ++$firstIndex;
                // };
            
                    $groupedData[$gameGroup]['subgroup'][$subgroupIndex]['games']['subgames'][] = $game;
                
            }
        }

        // $groupedData['Dragon/Tiger/Tie']['subgroup'][0]['games']['subgroup'];
        // print_r($groupedData['Dragon/Tiger/Tie']['subgroup'][0]['games']);
        // die;
        // $subGames = array_splice($groupedData['Dragon/Tiger/Tie']['subgroup'][0]['games'], 1);
        // $groupedData['Dragon/Tiger/Tie']['subgroup'][0]['games'][0]['subgames'] = $subGames;
        // print_r($groupedData);
        // die;
        $result = array_values($groupedData);
        if (empty($res)) {

            return ['status' => 'error', 'message' => 'No Record Found', 'data' => []];
        } else {

            return ['message' => 'Lottery Games', 'type' => 'success', 'data' => $result];
        }
    }