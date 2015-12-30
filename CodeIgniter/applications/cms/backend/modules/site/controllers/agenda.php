<?php

include_once(APPPATH . 'controllers/default_controller.php');

class Agenda extends Default_controller
{

    private $path;
    private $path_temporario;

    function __construct ()
    {
        // Define a tabela principal deste módulo
        $this->table_name = 'site_agenda';
        $this->exibir_coluna_ordem = false;
        parent::__construct($this->table_name);

        // Carrega a model e define a tabela principal
        $this->load->model('Agenda_model');
        $this->load->model('Cidades_model');
        $this->load->model('Clientes_model');

        // Passa parâmetros pro parent (Default_controller)
        $this->titulo = 'Agenda';
        $this->module = 'site';
        $this->controller = 'agenda';
    }

    /**
     * Função chamada quando entrar no módulo (lista os registros)
     */
    function listar ($pagina_atual = 1)
    {
        echo
        '
        <div class="col-md-9">
           <div class="box box-primary">
             <div class="box-body no-padding">
               <!-- THE CALENDAR -->
               <div id="calendar"></div>
             </div><!-- /.box-body -->
           </div><!-- /. box -->
         </div><!-- /.col -->
        

<script>
      $(function () {

        function ini_events(ele) {
          ele.each(function () {

            var eventObject = {
              title: $.trim($(this).text())
            };

            $(this).data('eventObject', eventObject);

            $(this).draggable({
              zIndex: 1070,
              revert: true, 
              revertDuration: 0  
            });

          });
        }
        ini_events($("#external-events div.external-event"));

        var date = new Date();
        var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();
        $("#calendar").fullCalendar({
          header: {
            left: "prev,next today",
            center: "title",
            right: "month,agendaWeek,agendaDay"
          },
          buttonText: {
            today: "today",
            month: "month",
            week: "week",
            day: "day"
          },
          //Random default events
          events: [
            {
              title: "All Day Event",
              start: new Date(y, m, 1),
              backgroundColor: "#f56954", 
              borderColor: "#f56954" 
            },
            {
              title: "Long Event",
              start: new Date(y, m, d - 5),
              end: new Date(y, m, d - 2),
              backgroundColor: "#f39c12", //yellow
              borderColor: "#f39c12" //yellow
            },
            {
              title: "Meeting",
              start: new Date(y, m, d, 10, 30),
              allDay: false,
              backgroundColor: "#0073b7", //Blue
              borderColor: "#0073b7" //Blue
            },
            {
              title: "Lunch",
              start: new Date(y, m, d, 12, 0),
              end: new Date(y, m, d, 14, 0),
              allDay: false,
              backgroundColor: "#00c0ef", //Info (aqua)
              borderColor: "#00c0ef" //Info (aqua)
            },
            {
              title: "Birthday Party",
              start: new Date(y, m, d + 1, 19, 0),
              end: new Date(y, m, d + 1, 22, 30),
              allDay: false,
              backgroundColor: "#00a65a", //Success (green)
              borderColor: "#00a65a" //Success (green)
            },
            {
              title: "Click for Google",
              start: new Date(y, m, 28),
              end: new Date(y, m, 29),
              url: "http://google.com/",
              backgroundColor: "#3c8dbc", //Primary (light-blue)
              borderColor: "#3c8dbc" //Primary (light-blue)
            }
          ],
          editable: true,
          droppable: true, // this allows things to be dropped onto the calendar !!!
          drop: function (date, allDay) { // this function is called when something is dropped

            var originalEventObject = $(this).data("eventObject");

            var copiedEventObject = $.extend({}, originalEventObject);

            copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;
            copiedEventObject.backgroundColor = $(this).css("background-color");
            copiedEventObject.borderColor = $(this).css("border-color");

            $("#calendar").fullCalendar("renderEvent", copiedEventObject, true);

            if ($("#drop-remove").is(":checked")) {
              $(this).remove();
            }

          }
        });

        /* ADDING EVENTS */
        var currColor = "#3c8dbc"; //Red by default
        //Color chooser button
        var colorChooser = $("#color-chooser-btn");
        $("#color-chooser > li > a").click(function (e) {
          e.preventDefault();
          //Save color
          currColor = $(this).css("color");
          //Add color effect to button
          $("#add-new-event").css({"background-color": currColor, "border-color": currColor});
        });
        $("#add-new-event").click(function (e) {
          e.preventDefault();

          var val = $("#new-event").val();
          if (val.length == 0) {
            return;
          }

          //Create events
          var event = $("<div />");
          event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
          event.html(val);
          $("#external-events").prepend(event);

          //Add draggable funtionality
          ini_events(event);

          //Remove event from text input
          $("#new-event").val("");
        });
      });
    </script>
';
    }

    function editar ($id = null)
    {
        // Array de dados para a view
        $dados = array();

        // Carrega a model
        $this->load->model('Agenda_model');

        // Obtém os dados
        if ($this->input->post('submit'))
        {
            // se tem post, obtém do formulário
            $dados = $this->input->post();
        } elseif ((int) $id > 0)
        {
            // se tem id, obtém da base
            $dados['registro'] = $this->Produtos_model->obter($id);
        }

        // Se tem post, salva os dados
        if ($this->input->post('submit'))
        {
            // Validação
            $this->form_validation->set_rules('registro[titulo]', 'Título', 'trim|required');
            $this->form_validation->set_rules('registro[descricao]', 'Descrição', 'trim|required');
            $this->form_validation->set_rules('registro[cliente]', 'Cliente', 'trim|required');
            $this->form_validation->set_rules('registro[data]', 'Data', 'trim|required');
            $this->form_validation->set_rules('registro[hora]', 'Hora', 'trim|required');
            if ($this->form_validation->run())
            {
                $dados['registro']['link'] = str_replace('-', '_', MY_Utils::removeSpecialChars(strtolower(utf8_decode($dados['agenda']['titulo']))));
                $dados['registro']['link'] = preg_replace('/_{2,}/', '_', $dados['agenda']['link']);

                $ok = $this->Agenda_model->salvar($dados['agenda']);
                $dados['agenda']['id'] = $ok;
            } else
            {
                if (rtrim(trim(strip_tags(validation_errors()))) == 'Unable to access an error message corresponding to your field name.')
                {
                    $dados['erro'] = 'O título deve ser único. Este título já está em uso.';
                } else
                {
                    $dados['erro'] = validation_errors();
                }
            }
        }

        // Definição dos campos
        // Codigo
        $campos = array();
        $campo = array();
        $campo['id'] = 'id';
        $campo['tamanho'] = 2;
        $campo['type'] = 'text';
        $campo['label'] = 'Código';
        $campo['placeholder'] = 'Código';
        $campo['value'] = $registro['id'];
        if ((int) $registro['id'] == 0)
        {
            $campo['attrs'] = 'disabled readonly';
        }
        $campos[] = $campo;

        // Descrição
        $campo = array();
        $campo['id'] = 'descricao';
        $campo['tamanho'] = 8;
        $campo['type'] = 'text';
        $campo['label'] = 'Descrição';
        $campo['placeholder'] = 'Descrição';
        $campo['value'] = $registro['descricao'];
        $campos[] = $campo;
        // Cidade
        $campo = array();
        $cidades = array();
        $arr_aux = $this->Cidades_model->listar();
        foreach ($arr_aux as $cidade)
        {
            $cidades[$cidade['id']] = $cidade['nome'];
        }
        $campo['id'] = 'cidade_id';
        $campo['tamanho'] = 2;
        $campo['type'] = 'dropdown';
        $campo['label'] = 'Cidade';
        $campo['placeholder'] = 'Cidade';
        $campo['value'] = $registro['cidade_id'];
        $campo['options'] = $cidades;
        $campos[] = $campo;
        // Cliente
        $campo = array();
        $cidades = array();
        $arr_aux = $this->Clientes_model->listar();
        foreach ($arr_aux as $cliente)
        {
            $clientes[$cliente['id']] = $cliente['nome'];
        }
        $campo['id'] = 'cliente_id';
        $campo['tamanho'] = 5;
        $campo['type'] = 'dropdown';
        $campo['label'] = 'Cliente';
        $campo['placeholder'] = 'Cliente';
        $campo['value'] = $registro['cliente_id'];
        $campo['options'] = $clientes;
        $campos[] = $campo;
        // Data
        $campo = array();
        $campo['id'] = 'data';
        $campo['tamanho'] = 3;
        $campo['type'] = 'date';
        $campo['label'] = 'Data';
        $campo['placeholder'] = 'Data';
        $campo['value'] = $registro['data'];
        $campos[] = $campo;
        // Hora
        $campo = array();
        $campo['id'] = 'hora';
        $campo['tamanho'] = 2;
        $campo['label'] = 'Hora';
        $campo['placeholder'] = 'Hora';
        $campo['value'] = $registro['hora'];
        $campo['class'] = 'timepicker';
        $campo['type'] = 'text';
        $campo['name'] = 'hora';
        $campo['pos'] = <<<HTML
<div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
HTML;
        $campos[] = $campo;
        // Concluido
        $campo = array();
        $campo['id'] = 'concluido';
        $campo['tamanho'] = 2;
        $campo['type'] = 'dropdown';
        $campo['label'] = 'Concluído';
        $campo['placeholder'] = 'Concluído';
        $campo['value'] = $registro['ativo'];
        $campo['options'] = array('1' => 'Sim', '0' => 'Não');
        $campos[] = $campo;

        $dados['campos'] = $campos;

        $dados['titulo'] = $this->titulo;

        // Adiciona JS
        $dados['custom_js'] = <<<JS
//Timepicker
$('#hora').timepicker(
{
    showInputs: false
});
JS;

        parent::load_view($dados);
    }

    /**
     * Remove um agenda
     * @param $id Código do agenda
     */
    function remover ($id)
    {

        parent::remover($id);
        redirect('site/agenda');
    }

}
