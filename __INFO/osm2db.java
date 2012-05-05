// Parses .osm-file into database-input
//
// Usage: osm2sql filename
// Output on Std-Out.
//
// 2008-09-27 Andreas "goblor" Hahn

import java.io.*;
import javax.xml.parsers.SAXParserFactory;  
import javax.xml.parsers.ParserConfigurationException;
import javax.xml.parsers.SAXParser;

import org.xml.sax.helpers.DefaultHandler;
import org.xml.sax.SAXException;
import org.xml.sax.Attributes;

public class osm2db
{

	public static void main (String argv[])
	{

		if (argv.length != 1) 
		{
			System.err.println ("Usage: osm2db filename");
			System.exit (1);
		}
		System.out.println("set character set utf8;");
		System.out.println("INSERT INTO `osm` (`way`, `name`, `role`) VALUES");
		try 	
		{
			SAXParser saxParser = SAXParserFactory.newInstance().newSAXParser();
			saxParser.parse( new File(argv [0]), new Handler() );
		}
		catch (Throwable t) 
		{
			t.printStackTrace ();
		}
		System.out.println(";");
		System.exit (0);
	}
}

class Handler extends DefaultHandler
{
	String nodeid="";
	String wayid="";
	String relationid="";
	boolean intag=false;
	boolean inmember=false;

	boolean starter=true;


	//Handles XML-Elements
	public void startElement (String uri, String localName, String qName, Attributes attrs) 
	{
		if (qName.equals("node"))
		{
			nodeid = "-"+attrs.getValue("id");
		}
		if (qName.equals("way"))
		{
			wayid =attrs.getValue("id");
		}
		if (qName.equals("relation"))
		{
			relationid = attrs.getValue("id");
		}
		if (qName.equals("tag"))
		{
			if (attrs.getValue("k").equals("name")){
				intag = true;
				if (!nodeid.isEmpty())
				{
					if (starter){
						starter = false;
					}else{
						System.out.print(",");
					}

					System.out.print("('");
					System.out.print(nodeid);
					System.out.print("', '");
					System.out.print(attrs.getValue("v").replaceAll("\\\\","\\\\\\\\").replaceAll("'","\\\\'"));
					System.out.print("', 'way')");
				}
				if (!wayid.isEmpty())
				{
					if (starter){
						starter = false;
					}else{
						System.out.print(",");
					}

					System.out.print("('");
					System.out.print(wayid);
					System.out.print("', '");
					System.out.print(attrs.getValue("v").replaceAll("\\\\","\\\\\\\\").replaceAll("'","\\\\'"));
					System.out.print("', 'way')");		
				}
				/*
				if (!relationid.isEmpty())
				{
					if (starter){
						starter = false;
					}else{
						System.out.print(",");
					}

					System.out.print("('");
					System.out.print(relationid);
					System.out.print("', '");
					System.out.print(attrs.getValue("v").replaceAll("\\\\","\\\\\\\\").replaceAll("'","\\\\'"));
					System.out.print("', 'outer')");		
				}
				*/		
			}
		}
	}

	public void endElement (String uri, String localName, String qName)  
	{
		if (qName.equals("node"))
		{
			nodeid = "";
		}
		if (qName.equals("way"))
		{
			wayid = "";
		}
		if (qName.equals("relation"))
		{
			relationid = "";
		}
		if (qName.equals("tag"))
		{
			intag = false;
		}
		if (qName.equals("member"))
		{
			inmember = false;
		}
	}


	public void startDocument() {}
	public void endDocument () {}
	public void characters (char buf [], int offset, int len) {}
}
