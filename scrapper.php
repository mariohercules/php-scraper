<?php

$estado = array("ac","al","am","ap","ba","ce","df","es","go","ma","mt","ms","mg","pa","pb","pr","pe","pi","rj","rn","ro","rs","rr","sc","se","sp","to");
$cargo  = array("governador", "senador", "deputado-federal", "deputado-estadual");

$stringHtmlBody = "";	
$stringHtml = "";
$stringText = "";

for ($i=0; $i < sizeof($estado); $i++) {
	
	$stringData = "";
	
	echo "Gerando o estado " . $estado[$i] . "<br>";
	
		for ($j=0; $j < sizeof($cargo); $j++) {
		
			echo "Gerando os candidatos " . $cargo[$j] . "<br>";
			
			echo "Gerando paths = " . "images/".$estado[$i]."/".$cargo[$j]."/" . "<br>";
			
			mkdir("images/".$estado[$i], 0700);
			mkdir("images/".$estado[$i]."/".$cargo[$j]."/", 0700);
			
			$html = file_get_contents('https://especiais.gazetadopovo.com.br/eleicoes/2018/candidatos/'.$estado[$i].'/'.$cargo[$j].'/');
			
			preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i',$html, $matches ); 
			
			for ($k=0; $k < sizeof($matches[1]); $k++) {
				
				$urlFile = $matches[ 1 ][ $k ];
				
				$file = explode("/",$urlFile);
				
				$fileName = $file[sizeof($file) - 1];
				
				$createfile = true;
				
				if (strpos($fileName, 'pesquisas-eleitorais') !== false) {
					$createfile = false;
				}
				
				if (strpos($fileName, 'opengraph') !== false) {
					$createfile = false;
				}
				
				if (strpos($fileName, 'logo') !== false) {
					$createfile = false;
				}
				
				if (strpos($fileName, 'Ibope') !== false) {
					$createfile = false;
				}
				
				if (strpos($fileName, 'sensus') !== false) {
					$createfile = false;
				}
				
				if ($createfile) {
				
					$content = file_get_contents($urlFile);
					
					$fp = fopen("images/".$estado[$i]."/".$cargo[$j]."/".$fileName, "w");
					
					fwrite($fp, $content);
					fclose($fp);				
					
					//$stringData .= "<img src="."images/".$estado[$i]."/".$cargo[$j]."/".$fileName."> <br/>"; 
					
					$nome_candidato = str_replace(".jpg", "", $fileName);
					$nome_candidato = ucwords(str_replace("-"," ", $nome_candidato));
					$nome_candidato = preg_replace('/[0-9]+/', '', $nome_candidato);
					
					$tr = "
			            <tr>
							<td align=\"center\"><img src=\"".$cargo[$j]."/".$fileName."\"></td>
							<td align=\"center\">".$nome_candidato."</td>
							<td align=\"center\">".strtoupper($estado[$i])."</td>
							<td align=\"center\">".strtoupper($cargo[$j])."</td>
						</tr>					
					";
					
					
					//$stringHtml .= "<img src=\"".$cargo[$j]."/".$fileName."\"> <br/>"; 
					
					$stringHtml .= $tr;
					
					
					$stringText .= "<img src=\""."images/".$estado[$i]."/".$cargo[$j]."/".$fileName."\"> \n"; 
					
									
				} 
				 
			}
			
			
		
		}
		
		
		$stringHtmlBody = "
		    <html>
		    <body>
			<table style=\"width: 100%\">
			<tbody>
			<tr>
				<td align=\"center\"><b>Candidato</b></td>
				<td align=\"center\"><b>Nome</b></td>
				<td align=\"center\"><b>Estado</b></td>
				<td align=\"center\"><b>Cargo</b></td>
			</tr>
			".$stringHtml."
			</tbody>
			</table>		
		    </body>
		    </html>
		";
		
		
		$html = "index.html"; // or .php   
		$fh = fopen("images/".$estado[$i]."/".$html, 'w'); // or die("error");  
		  
		fwrite($fh, $stringHtmlBody);
		fclose($fh);
		
		$txt = "index.txt"; // or .php   
		$fh = fopen("images/".$estado[$i]."/".$txt, 'a'); // or die("error");  
		  
		fwrite($fh, "\n". $stringText);
		fclose($fh);	
		
		$stringHtmlBody = "";	
		$stringHtml = "";
		$stringText = "";
			
}

?>
