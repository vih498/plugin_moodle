define(['jquery'], function($) {

    return {

        init: function() {

            // Busca os dados reais via AJAX PHP
            $.ajax({
                url: M.cfg.wwwroot + '/local/studentanalytics/ajax/get_metrics.php',
                method: 'GET',
                success: function(response) {

                    let data = JSON.parse(response);

                    let labels = data.map(x => x.user);
                    let valores = data.map(x => x.events);

                    let ctx = document.getElementById('graficoEventos');

                    if (!ctx) {
                        console.warn("Elemento #graficoEventos não encontrado.");
                        return;
                    }

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Eventos nos últimos dias',
                                data: valores
                            }]
                        }
                    });
                },
                error: function() {
                    console.error("Erro no AJAX get_metrics.php");
                }
            });
        },

        init_realtime: function(courseid) {

            $.ajax({
                url: M.cfg.wwwroot + '/local/studentanalytics/ajax/get_realtime_metrics.php?courseid=' + courseid,
                method: 'GET',
                success: function(response) {

                    let data = JSON.parse(response);

                    let labels = data.map(x => x.name);
                    let acessos = data.map(x => x.events);
                    let notas = data.map(x => x.avg_grade);

                    let ctx = document.getElementById('graficoEventos');

                    if (!ctx) {
                        console.warn("Elemento #graficoEventos não encontrado.");
                        return;
                    }

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Eventos no curso',
                                    data: acessos
                                },
                                {
                                    label: 'Média das notas',
                                    data: notas
                                }
                            ]
                        }
                    });
                },
                error: function() {
                    console.error("Erro no AJAX get_realtime_metrics.php");
                }
            });

        }
    };
});
