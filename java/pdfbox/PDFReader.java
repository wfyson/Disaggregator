import java.io.*;
import org.apache.pdfbox.pdmodel.*;
import org.apache.pdfbox.util.*;

public class PDFReader
{
	public static void main(String[] args)
	{
		new PDFReader(args[0]);	
	}

	public PDFReader(String path)
	{
		PDDocument pd;
		BufferedWriter wr;
		try
		{
			File input = new File(path); //The PDF file we want to extract...

			File output = new File("output.txt"); //Store extracted data here...

			pd = PDDocument.load(input);
			System.out.println(pd.getNumberOfPages());
			if(pd.isEncrypted())
			{
				try
				{
					pd.decrypt("");
					pd.setAllSecurityToBeRemoved(true);
				}
				catch (Exception e)
				{
					throw new Exception("The document is encrypted and we can't decrypt it");
				}
			}
			else
			{

			}
			pd.save("Copy" + path);		
			PDFTextStripper stripper = new PDFTextStripper();
	//		stripper.setStartPage(3);
	//		stripper.setEndPage(5);
		
			wr = new BufferedWriter(new OutputStreamWriter(new FileOutputStream(output)));
			stripper.writeText(pd, wr);
			if(pd != null)
			{
				pd.close();
			}		
			wr.close();
		}
		catch (Exception e)
		{
			e.printStackTrace();
		}
	}
}
