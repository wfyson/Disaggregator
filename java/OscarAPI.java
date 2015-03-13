import java.util.*;
import uk.ac.cam.ch.wwmm.oscar.Oscar;
import uk.ac.cam.ch.wwmm.oscar.chemnamedict.entities.*;



public class OscarAPI
{
	public static void main(String[] args)
	{
	//	new OscarAPI(args[0]);
		System.out.println(args[0]);
	}

	public OscarAPI(String search)
	{
		Oscar oscar = new Oscar();
		List<ResolvedNamedEntity> entities = oscar.findAndResolveNamedEntities(search);
		for (ResolvedNamedEntity ne : entities)
		{
			System.out.println(ne.getSurface());	
		}
	}
}
