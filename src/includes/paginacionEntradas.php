<!--html para la paginación de la página de listar usuarios-->
<div class="row justify-content-center">
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php

                if ($parametros["pagina"] == 1){ //si se encuentra en la primera página, el botón para ir hacia atrás se desactiva

                    echo "<li class='page-item disabled'>
                    <a class='page-link' href='' aria-label='Previous'>
                        <span aria-hidden='true'>&laquo;</span>
                        <span class='sr-only'>Previous</span>
                    </a>
                    </li>";
                    
                //si no, cada vez que se pulse sobre el botón 'previous' se dirigirá a la página anterior
                } else {  ?> 

                    <li class="page-item">
                        <a class="page-link" href="../vistas/index.php?accion=listarEntradas&pagina=<?php echo $parametros["pagina"] - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>

                <?php  } ?> 
           
                <?php
                    /**
                     * en este bucle for se mostrarán el número de páginas según el número de registros y 
                     * de filas por página
                     */
                    for ($i = 1; $i <= $parametros["num_paginas"]; $i ++) { 
                        
                        if ($parametros["pagina"] == $i){

                            echo "<li class='page-item active'><a class='page-link' href='../vistas/index.php?accion=listarEntradas&pagina=$i'>$i</a></li>";

                        } else {

                            echo "<li class='page-item'><a class='page-link' href='../vistas/index.php?accion=listarEntradas&pagina=$i'>$i</a></li>";
                        }
                    }

                ?>

                <?php 

                    //si se encuentra en la última página, el botón para ir hacia delante se desactiva
                    if ($parametros["pagina"] == $parametros["num_paginas"]){ ?> 

                        <li class="page-item disabled">
                            <a class="page-link" href="" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                        
                   <?php } else { ?>

                    <!--si no, cada vez que se pulse sobre el botón 'previous' se dirigirá a la página siguiente-->
                    <li class="page-item">
                        <a class="page-link" href="../vistas/index.php?accion=listarEntradas&pagina=<?php echo $parametros["pagina"] + 1?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>

                    <?php } ?>
            
        </ul>
    </nav>
</div>