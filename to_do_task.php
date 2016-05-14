<!DOCTYPE html>
<html lang="en" ng-app='store'>

<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular.min.js"></script>
<style>
.material-switch > input[type="checkbox"] {
    display: none;
}

.material-switch > label {
    cursor: pointer;
    height: 0px;
    position: relative;
    width: 40px;
}

.material-switch > label::before {
    background: rgb(0, 0, 0);
    box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
    border-radius: 8px;
    content: '';
    height: 16px;
    margin-top: -8px;
    position:absolute;
    opacity: 0.3;
    transition: all 0.4s ease-in-out;
    width: 40px;
}
.material-switch > label::after {
    background: rgb(255, 255, 255);
    border-radius: 16px;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
    content: '';
    height: 24px;
    left: -4px;
    margin-top: -8px;
    position: absolute;
    top: -4px;
    transition: all 0.3s ease-in-out;
    width: 24px;
}
.material-switch > input[type="checkbox"]:checked + label::before {
    background: inherit;
    opacity: 0.5;
}
.material-switch > input[type="checkbox"]:checked + label::after {
    background: inherit;
    left: 20px;
}
</style>
</head>
<body>

<div class="container">
  <div class="jumbotron" style="font-variant-ligatures: initial;color: #fff;background-color: rgb(47, 98, 136);">
      <img src="http://cdn.theatlantic.com/assets/media/img/photo/2015/11/images-from-the-2016-sony-world-pho/s01_130921474920553591/main_900.jpg?1448476701" alt="perk" class="img-circle " style=" width: 151px;height: 125px;margin-top: 25px;float: left;">
    <div class="col-lm-12" style="text-align:center;">
        <h2>To-Do List</h2>
    </div>
      </div>
    <div class="row" style="margin: 10px!important;">
        <div ng-controller="TodoCtrl">
            <div class="col-lm-12" style="text-align:right;">
                {{getUncompletedCount()}} of {{todos.length}} remaining
                <button ng-click="archiveCompleted()" class="btn btn-primary">Archive Completed</button>
            </div>
            <br/>
            <form style="text-align: center;" class="form-group">
                <input type="text" ng-model="todoText" size="30" placeholder="enter new todo here" class="form-control" autofocus style="width:87%!important;float:left;margin-right: 10px;"/>
                <button ng-click="addTodo()" ng-disabled="!todoText" class="btn-success"  style="height: 32px;">Add</button>
                <br>
            </form>

            <ul class="nav nav-pills nav-stacked tag-list">
                <li class="col-md-12 list-group-item" ng-repeat="todo in todos"/>
                    <a href="#" style="background-color: #2F6288; color:#FFF;">
                        <div class="material-switch pull-right">
                            <input id="someSwitchOptionSuccess{{todo['id']}}" name="someSwitchOption001" ng-click="updateTodo(todo)" type="checkbox"  ng-model="todo.done"/>
                            <label for="someSwitchOptionSuccess{{todo['id']}}" class="label-success"></label>
                        </div>
                        <button ng-click="deleteTodo(todo)" class="btn-warning">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                    <span class="done-{{todo.done}}">{{todo['title']}}</span>

                </li>
            </ul>
        </div>

    </div>
    </div>
</div>
</body>
<script type="text/javascript">
//function to fetch data from database
function fetchData(){
                       var arr_id       =   [];
                       var arr_title    =   [];
                       var arr_done     =   [];
                       var result_array =   [];
                        $.ajax({
                            url         : 'ajax_to_do_task.php',
                            type        : 'GET',
                            data        : {action:'fetch'},
                            async       :  false, //blocks window close
                            success     : function(result) 
                                                {
                                                    if(result!='no_records')
                                                    {
                                                        var arr     =   JSON.parse(result);
                                                        for(i=0;i<arr.length;i++)
                                                        {
                                                            arr_id[i]           =   arr[i]['id'];  
                                                            arr_title[i]        =   arr[i]['title'];  
                                                            arr_done[i]         =   arr[i]['done']=='true'?true:false;                
                                                        }
                                                    }
                                                }
                        });
                        
                        if(arr_id.length>0)
                        {
                            for(i=0;i<arr_id.length;i++)
                            {
                                result_array.push({"id":arr_id[i],"title":arr_title[i],"done":arr_done[i]});
                            }
                        }
                        return result_array;
                }
    (function (){
        

            var app             =   angular.module('store',[])
            app.controller('TodoCtrl', function ($scope,$http) {
            $scope.todos        =   [];
            $scope.array        =   fetchData();
            angular.forEach($scope.array, function(element) 
            {
                    $scope.arr  = element;
                    $scope.todos.push($scope.arr);
            })

//function to add
$scope.addTodo              = function () {
                                            $http.get('ajax_to_do_task.php', {
                                                params: { title: $scope.todoText,action:'add' }
                                            }) .then(function(response) {
                                                     $scope.id= response.data;
                                                });
                                            $scope.todos.push({id: $scope.id,title: $scope.todoText, done: false});
                                            $scope.todoText = ''; // clears input
                            };
//function to archieve 
$scope.archiveCompleted     = function () {
                                                $http.get('ajax_to_do_task.php', {
                                                    params: {action:'delete_all' }
                                                })
                                            // Not saving completed todos in this version.
                                            $scope.todos = $scope.todos.filter(function (t) { return !t.done; });
                            };
//function to delete record
$scope.deleteTodo           = function (todo) {
                                            $http.get('ajax_to_do_task.php', {
                                                params: { id:todo['id'],action:'delete' }
                                            })
                                            $scope.todos = $scope.todos.filter(function (t) { 
                                                return t !== todo; 
                                            });
                            };
//function to get the count of records
$scope.getUncompletedCount = function () {
                                            var count = 0;
                                            angular.forEach($scope.todos, function (todo) {
                                            if (!todo.done) count++;
                                            });
                                            return count;
                            };
//function to update 
$scope.updateTodo           =   function(todo){
                                            $http.get('ajax_to_do_task.php', {
                                                params: { id:todo['id'],action:'update' }
                                            })

                            }                            
});
    })()
</script>
</html>