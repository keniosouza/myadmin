<!DOCTYPE html>
<html lang="pt-br">
<style>
    html, body, div, span, applet, object, iframe,
    h1, h2, h3, h4, h5, h6, p, blockquote, pre,
    a, abbr, acronym, address, big, cite, code,
    del, dfn, em, img, ins, kbd, q, s, samp,
    small, strike, strong, sub, sup, tt, var,
    b, u, i, center,
    dl, dt, dd, ol, ul, li,
    fieldset, form, label, legend,
    table, caption, tbody, tfoot, thead, tr, th, td,
    article, aside, canvas, details, embed,
    figure, figcaption, footer, header, hgroup,
    menu, nav, output, ruby, section, summary,
    time, mark, audio, video {
        margin: 0;
        padding: 0;
        border: 0;
        font-size: 100%;
        font: inherit;
        vertical-align: baseline;
    }

    /* HTML5 display-role reset for older browsers */
    article, aside, details, figcaption, figure,
    footer, header, hgroup, menu, nav, section {
        display: block;
    }

    body {
        line-height: 1;
    }

    ol, ul {
        list-style: none;
    }

    blockquote, q {
        quotes: none;
    }

    blockquote:before, blockquote:after,
    q:before, q:after {
        content: '';
        content: none;
    }

    table {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    table td, table th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    table tr:hover {
        background-color: #ddd;
    }

    table th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #FFFFFF;
        color: #333333;
    }

    @page {
        size: 29.7cm 21cm!important;
        margin-left: 1.5cm !important;
        margin-top: 1.5cm !important;
        margin-right: 1.5cm !important;
        margin-bottom: 1.5cm !important;
    }
</style>
<body>
<div>

    <table>

        <thead>

        <tr>

            <th>

                Ativo

            </th>

            <th>

                Banco

            </th>

            <th>

                Categoria

            </th>

            <th>

                Valor

            </th>

            <th>

                Início

            </th>

            <th>

                Fim

            </th>

            <th>

                Duração

            </th>

        </tr>
        </thead>


        <?php

        /** Variaveis para contagem */
        $valueTotal = null;

        /** Listo todos os regitros */
        foreach ($resultFinancialEntriesReport as $keyResult => $result) {

            /** Somo os valores total */
            $valueTotal = (double)$valueTotal + (double)$result->entrie_value;

            ?>

            <tbody>

            <tr>

                <td>

                    <?php echo $result->active === 'S' ? 'Sim' : 'Não'; ?>

                </td>

                <td>

                    <?php echo $result->categories_description; ?>

                </td>

                <td>

                    <?php echo $result->accounts_description; ?>

                </td>

                <td>

                    R$ <?php echo $result->entrie_value; ?>

                </td>

                <td>

                    <?php echo date('d/m/Y', strtotime($result->start_date)); ?>

                </td>

                <td>

                    <?php echo date('d/m/Y', strtotime($result->end_date)); ?>

                </td>

                <td>

                    <?php echo $result->duration; ?>(Meses)

                </td>

            </tr>

            <tr>

                <td>

                    Cliente:

                </td>

                <td colspan="6">

                    <?php echo strtoupper($result->client_name); ?>

                </td>

            </tr>

        </tbody>

        <?php } ?>

        <tfoot>

        <tr>
            <th>

                Registros: <?php echo count($resultFinancialEntriesReport)?>

            </th>

            <th colspan="6">

                Valor Total: R$ <?php echo (double)$valueTotal?>

            </th>

        </tr>

        </tfoot>

    </table>

</div>
</body>
</html>