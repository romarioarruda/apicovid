# apicovid

Api de dados do covid-19 no Brasil.

# Endpoints

> https://romarioarruda.dev/apicovid/dados
Retorna os casos acumulados por unidade federativa (UF).

> https://romarioarruda.dev/apicovid/dados?uf=alguma_uf
Filtra os casos por unidade federativa (UF).

> https://romarioarruda.dev/apicovid/dados/totais
Retorna o número total de casos e óbitos acumulados no país.

> https://romarioarruda.dev/apicovid/dados/recuperados
Retorna os números totais de recuperados por data de notificação.
O default é retornar apenas os dados do mês corrente.

> https://romarioarruda.dev/apicovid/dados/recuperados?data_ini=data_inicial
Retorna os números totais de recuperados a partir de uma data.

> https://romarioarruda.dev/apicovid/dados/recuperados?data_ini=data_inicial&data_fim=data_final
Retorna os números totais de recuperados entre duas datas.

> https://romarioarruda.dev/apicovid/dados/obitos
Retorna os números totais de óbitos por data de notificação.
O default é retornar apenas os dados do mês corrente.

> https://romarioarruda.dev/apicovid/dados/obitos?data_ini=data_inicial
Retorna os números totais de óbitos a partir de uma data.

> https://romarioarruda.dev/apicovid/dados/obitos?data_ini=data_inicial&data_fim=data_final
Retorna os números totais de óbitos entre duas datas.
