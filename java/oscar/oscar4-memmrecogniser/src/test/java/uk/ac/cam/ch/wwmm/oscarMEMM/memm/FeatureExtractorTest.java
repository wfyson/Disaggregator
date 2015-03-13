package uk.ac.cam.ch.wwmm.oscarMEMM.memm;

import static org.junit.Assert.assertEquals;

import java.util.Arrays;
import java.util.List;
import java.util.Set;

import org.junit.Test;

import uk.ac.cam.ch.wwmm.oscar.chemnamedict.core.ChemNameDictRegistry;
import uk.ac.cam.ch.wwmm.oscar.document.TokenSequence;
import uk.ac.cam.ch.wwmm.oscarrecogniser.extractedtrainingdata.ExtractedTrainingData;
import uk.ac.cam.ch.wwmm.oscarrecogniser.tokenanalysis.NGram;
import uk.ac.cam.ch.wwmm.oscartokeniser.Tokeniser;

/**
 * @author sea36
 */
public class FeatureExtractorTest {

    @Test
    public void testFeatureExtractor() {
        // Regression test introduced pre-refactoring
        String s = "We have also described that benzoxasilepines can be condensed with benzaldehydes.";
        Tokeniser tokeniser = Tokeniser.getDefaultInstance();
        TokenSequence tokSeq = tokeniser.tokenise(s);
        Set <String> defaultChemNames = ChemNameDictRegistry.getDefaultInstance().getAllNames();
        
        List<FeatureList> features = FeatureExtractor.extractFeatures(
        		tokSeq, NGram.getInstance(), ExtractedTrainingData.loadExtractedTrainingData("chempapers"),
        		defaultChemNames);

        /*
        assertArrayMatch(Arrays.asList("4G=^We$", "c0:w=We", "c0:wts=We", "c0:ws=42", "c0:s=", "c1:w=have", "c1:wts=have", "c1:ws=1", "c1:s=", "bg:0:1:w=We__ws=1", "bg:0:1:ws=42__ws=1"), features.get(0));
        assertArrayMatch(Arrays.asList("4G=^hav", "3G=hav", "4G=have", "2G=av", "3G=ave", "4G=ave$", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "$STOP:CLOSEDCLASS", "$STOP:NCW", "$STOP:UDW", "c-1:w=We", "c-1:wts=We", "c-1:ws=42", "c-1:s=", "c0:w=have", "c0:wts=have", "c0:ws=1", "c0:s=", "c1:w=also", "c1:wts=also", "c1:ws=1", "c1:s=", "bg:-1:0:w=We__ws=1", "bg:-1:0:ws=42__ws=1", "bg:0:1:ws=1__ws=1"), features.get(1));
        assertArrayMatch(Arrays.asList("4G=^als", "3G=als", "4G=also", "2G=ls", "3G=lso", "4G=lso$", "ngram-=GP", "ngram-=GP", "ngram-=GP", "ngram-=GP", "ngram-=GP", "ngram-=GP", "ngram-=GP", "ngram-=GP", "ngram-=GP", "ngram-=GP", "ngram-=GP", "ngram-=GP", "ngram-=GP", "ngram-=GP", "ngram-=GP", "$STOP:UDW", "c-1:w=have", "c-1:wts=have", "c-1:ws=1", "c-1:s=", "c0:w=also", "c0:wts=also", "c0:ws=1", "c0:s=", "c1:w=described", "c1:wts=described", "c1:ws=1", "c1:s=", "bg:-1:0:ws=1__ws=1", "bg:0:1:ws=1__ws=1"), features.get(2));
        assertArrayMatch(Arrays.asList("4G=^des", "3G=des", "4G=desc", "2G=es", "3G=esc", "4G=escr", "1G=s", "2G=sc", "3G=scr", "4G=scri", "1G=c", "2G=cr", "3G=cri", "4G=crib", "1G=r", "2G=ri", "3G=rib", "4G=ribe", "1G=i", "2G=ib", "3G=ibe", "4G=ibed", "1G=b", "2G=be", "3G=bed", "4G=bed$", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "$STOP:UDW", "c-1:w=also", "c-1:wts=also", "c-1:ws=1", "c-1:s=", "c0:w=described", "c0:wts=described", "c0:ws=1", "c0:s=", "c1:w=that", "c1:wts=that", "c1:ws=1", "c1:s=", "bg:-1:0:ws=1__ws=1", "bg:0:1:ws=1__ws=1"), features.get(3));
        assertArrayMatch(Arrays.asList("4G=^tha", "3G=tha", "4G=that", "2G=ha", "3G=hat", "4G=hat$", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "$STOP:CLOSEDCLASS", "$STOP:UDW", "c-1:w=described", "c-1:wts=described", "c-1:ws=1", "c-1:s=", "c0:w=that", "c0:wts=that", "c0:ws=1", "c0:s=", "c1:w=benzoxasilepines", "c1:wts=benzoxasilepine", "c1:ws=1", "c1:s=s", "c1:ct=CMS", "bg:-1:0:ws=1__ws=1", "bg:0:1:ws=1__ws=1", "bg:0:1:ws=1__ct=CMS"), features.get(4));
        assertArrayMatch(Arrays.asList("4G=^ben", "3G=ben", "4G=benz", "2G=en", "3G=enz", "4G=enzo", "1G=n", "2G=nz", "3G=nzo", "4G=nzox", "1G=z", "2G=zo", "3G=zox", "4G=zoxa", "1G=o", "2G=ox", "3G=oxa", "4G=oxas", "1G=x", "2G=xa", "3G=xas", "4G=xasi", "1G=a", "2G=as", "3G=asi", "4G=asil", "1G=s", "2G=si", "3G=sil", "4G=sile", "1G=i", "2G=il", "3G=ile", "4G=ilep", "1G=l", "2G=le", "3G=lep", "4G=lepi", "1G=e", "2G=ep", "3G=epi", "4G=epin", "1G=p", "2G=pi", "3G=pin", "4G=pine", "1G=i", "2G=in", "3G=ine", "4G=ines", "1G=n", "2G=ne", "3G=nes", "4G=nes$", "ngram+=CMS", "ngram+=CMS", "ngram+=CMS", "ngram+=CMS", "ngram+=CMS", "ngram+=CMS", "ngram+=CMS", "ngram+=CMS", "ngram+=CMS", "ngram+=CMS", "ngram+=CMS", "c-1:w=that", "c-1:wts=that", "c-1:ws=1", "c-1:s=", "c0:w=benzoxasilepines", "c0:wts=benzoxasilepine", "c0:ws=1", "c0:s=s", "c0:ct=CMS", "c1:w=can", "c1:wts=can", "c1:ws=1", "c1:s=", "bg:-1:0:ws=1__ws=1", "bg:-1:0:ws=1__ct=CMS", "bg:0:1:ws=1__w=can", "bg:0:1:ws=1__ws=1", "bg:0:1:ct=CMS__w=can", "bg:0:1:ct=CMS__ws=1"), features.get(5));
        assertArrayMatch(Arrays.asList("4G=^can", "3G=can", "4G=can$", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "$STOP:CLOSEDCLASS", "$STOP:UDW", "c-1:w=benzoxasilepines", "c-1:wts=benzoxasilepine", "c-1:ws=1", "c-1:s=s", "c-1:ct=CMS", "c0:w=can", "c0:wts=can", "c0:ws=1", "c0:s=", "c1:w=be", "c1:wts=be", "c1:ws=1", "c1:s=", "bg:-1:0:ws=1__w=can", "bg:-1:0:ws=1__ws=1", "bg:-1:0:ct=CMS__w=can", "bg:-1:0:ct=CMS__ws=1", "bg:0:1:w=can__w=be", "bg:0:1:w=can__ws=1", "bg:0:1:ws=1__w=be", "bg:0:1:ws=1__ws=1"), features.get(6));
        assertArrayMatch(Arrays.asList("4G=^be$", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "$STOP:CLOSEDCLASS", "$STOP:UDW", "c-1:w=can", "c-1:wts=can", "c-1:ws=1", "c-1:s=", "c0:w=be", "c0:wts=be", "c0:ws=1", "c0:s=", "c1:w=condensed", "c1:wts=condensed", "c1:ws=1", "c1:s=", "bg:-1:0:w=can__w=be", "bg:-1:0:w=can__ws=1", "bg:-1:0:ws=1__w=be", "bg:-1:0:ws=1__ws=1", "bg:0:1:w=be__ws=1", "bg:0:1:ws=1__ws=1"), features.get(7));
        assertArrayMatch(Arrays.asList("4G=^con", "3G=con", "4G=cond", "2G=on", "3G=ond", "4G=onde", "1G=n", "2G=nd", "3G=nde", "4G=nden", "1G=d", "2G=de", "3G=den", "4G=dens", "1G=e", "2G=en", "3G=ens", "4G=ense", "1G=n", "2G=ns", "3G=nse", "4G=nsed", "1G=s", "2G=se", "3G=sed", "4G=sed$", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "$STOP:UDW", "c-1:w=be", "c-1:wts=be", "c-1:ws=1", "c-1:s=", "c0:w=condensed", "c0:wts=condensed", "c0:ws=1", "c0:s=", "c1:w=with", "c1:wts=with", "c1:ws=1", "c1:s=", "bg:-1:0:w=be__ws=1", "bg:-1:0:ws=1__ws=1", "bg:0:1:ws=1__ws=1"), features.get(8));
        assertArrayMatch(Arrays.asList("4G=^wit", "3G=wit", "4G=with", "2G=it", "3G=ith", "4G=ith$", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "ngram-=CM", "$STOP:CLOSEDCLASS", "$STOP:UDW", "c-1:w=condensed", "c-1:wts=condensed", "c-1:ws=1", "c-1:s=", "c0:w=with", "c0:wts=with", "c0:ws=1", "c0:s=", "c1:w=benzaldehydes", "c1:wts=benzaldehyde", "c1:ws=1", "c1:s=s", "c1:ct=CMS", "bg:-1:0:ws=1__ws=1", "bg:0:1:ws=1__ws=1", "bg:0:1:ws=1__ct=CMS"), features.get(9));
        assertArrayMatch(Arrays.asList("4G=^ben", "3G=ben", "4G=benz", "2G=en", "3G=enz", "4G=enza", "1G=n", "2G=nz", "3G=nza", "4G=nzal", "1G=z", "2G=za", "3G=zal", "4G=zald", "1G=a", "2G=al", "3G=ald", "4G=alde", "1G=l", "2G=ld", "3G=lde", "4G=ldeh", "1G=d", "2G=de", "3G=deh", "4G=dehy", "1G=e", "2G=eh", "3G=ehy", "4G=ehyd", "1G=h", "2G=hy", "3G=hyd", "4G=hyde", "1G=y", "2G=yd", "3G=yde", "4G=ydes", "1G=d", "2G=de", "3G=des", "4G=des$", "ngram+=CMS", "ngram+=CMS", "ngram+=CMS", "ngram+=CMS", "ngram+=CMS", "ngram+=CMS", "ngram+=CMS", "ngram+=CMS", "ngram+=CMS", "c-1:w=with", "c-1:wts=with", "c-1:ws=1", "c-1:s=", "c0:w=benzaldehydes", "c0:wts=benzaldehyde", "c0:ws=1", "c0:s=s", "c0:ct=CMS", "c1:w=.", "c1:wts=.", "c1:s=", "bg:-1:0:ws=1__ws=1", "bg:-1:0:ws=1__ct=CMS", "bg:0:1:ws=1__w=.", "bg:0:1:ct=CMS__w=."), features.get(10));
        assertArrayMatch(Arrays.asList("c-1:w=benzaldehydes", "c-1:wts=benzaldehyde", "c-1:ws=1", "c-1:s=s", "c-1:ct=CMS", "c0:w=.", "c0:wts=.", "c0:s=", "bg:-1:0:ws=1__w=.", "bg:-1:0:ct=CMS__w=."), features.get(11));
        */

        assertArrayMatch(Arrays.asList("4G=^We$","$STOP:NCNW","c0:w=We","c0:wts=We","c0:ws=42","c0:s=","c1:w=have","c1:wts=have","c1:ws=1","c1:s=","bg:0:1:w=We__ws=1","bg:0:1:ws=42__ws=1"), features.get(0).toList());
        assertArrayMatch(Arrays.asList("4G=^hav","3G=hav","4G=have","2G=av","3G=ave","4G=ave$","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","$STOP:CLOSEDCLASS","$STOP:NCW","$STOP:UDW","c-1:w=We","c-1:wts=We","c-1:ws=42","c-1:s=","c0:w=have","c0:wts=have","c0:ws=1","c0:s=","c1:w=also","c1:wts=also","c1:ws=1","c1:s=","bg:-1:0:w=We__ws=1","bg:-1:0:ws=42__ws=1","bg:0:1:ws=1__ws=1"), features.get(1).toList());
        assertArrayMatch(Arrays.asList("4G=^als","3G=als","4G=also","2G=ls","3G=lso","4G=lso$","ngram-=GP","ngram-=GP","ngram-=GP","ngram-=GP","ngram-=GP","ngram-=GP","ngram-=GP","ngram-=GP","ngram-=GP","ngram-=GP","ngram-=GP","ngram-=GP","ngram-=GP","ngram-=GP","ngram-=GP","$STOP:NCW","$STOP:UDW","c-1:w=have","c-1:wts=have","c-1:ws=1","c-1:s=","c0:w=also","c0:wts=also","c0:ws=1","c0:s=","c1:w=described","c1:wts=described","c1:ws=1","c1:s=","bg:-1:0:ws=1__ws=1","bg:0:1:ws=1__ws=1"), features.get(2).toList());
        assertArrayMatch(Arrays.asList("4G=^des","3G=des","4G=desc","2G=es","3G=esc","4G=escr","1G=s","2G=sc","3G=scr","4G=scri","1G=c","2G=cr","3G=cri","4G=crib","1G=r","2G=ri","3G=rib","4G=ribe","1G=i","2G=ib","3G=ibe","4G=ibed","1G=b","2G=be","3G=bed","4G=bed$","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","$STOP:NCW","$STOP:UDW","c-1:w=also","c-1:wts=also","c-1:ws=1","c-1:s=","c0:w=described","c0:wts=described","c0:ws=1","c0:s=","c1:w=that","c1:wts=that","c1:ws=1","c1:s=","bg:-1:0:ws=1__ws=1","bg:0:1:ws=1__ws=1"), features.get(3).toList());
        assertArrayMatch(Arrays.asList("4G=^tha","3G=tha","4G=that","2G=ha","3G=hat","4G=hat$","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","$STOP:CLOSEDCLASS","$STOP:NCW","$STOP:UDW","c-1:w=described","c-1:wts=described","c-1:ws=1","c-1:s=","c0:w=that","c0:wts=that","c0:ws=1","c0:s=","c1:w=benzoxasilepines","c1:wts=benzoxasilepine","c1:ws=1","c1:s=s","c1:ct=CMS","bg:-1:0:ws=1__ws=1","bg:0:1:ws=1__ws=1","bg:0:1:ws=1__ct=CMS"), features.get(4).toList());
        assertArrayMatch(Arrays.asList("4G=^ben","3G=ben","4G=benz","2G=en","3G=enz","4G=enzo","1G=n","2G=nz","3G=nzo","4G=nzox","1G=z","2G=zo","3G=zox","4G=zoxa","1G=o","2G=ox","3G=oxa","4G=oxas","1G=x","2G=xa","3G=xas","4G=xasi","1G=a","2G=as","3G=asi","4G=asil","1G=s","2G=si","3G=sil","4G=sile","1G=i","2G=il","3G=ile","4G=ilep","1G=l","2G=le","3G=lep","4G=lepi","1G=e","2G=ep","3G=epi","4G=epin","1G=p","2G=pi","3G=pin","4G=pine","1G=i","2G=in","3G=ine","4G=ines","1G=n","2G=ne","3G=nes","4G=nes$","ngram+=CMS","ngram+=CMS","ngram+=CMS","ngram+=CMS","ngram+=CMS","ngram+=CMS","ngram+=CMS","ngram+=CMS","ngram+=CMS","ngram+=CMS","ngram+=CMS","c-1:w=that","c-1:wts=that","c-1:ws=1","c-1:s=","c0:w=benzoxasilepines","c0:wts=benzoxasilepine","c0:ws=1","c0:s=s","c0:ct=CMS","c1:w=can","c1:wts=can","c1:ws=1","c1:s=","bg:-1:0:ws=1__ws=1","bg:-1:0:ws=1__ct=CMS","bg:0:1:ws=1__w=can","bg:0:1:ws=1__ws=1","bg:0:1:ct=CMS__w=can","bg:0:1:ct=CMS__ws=1"), features.get(5).toList());
        assertArrayMatch(Arrays.asList("4G=^can","3G=can","4G=can$","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","$STOP:CLOSEDCLASS","$STOP:NCW","$STOP:UDW","c-1:w=benzoxasilepines","c-1:wts=benzoxasilepine","c-1:ws=1","c-1:s=s","c-1:ct=CMS","c0:w=can","c0:wts=can","c0:ws=1","c0:s=","c1:w=be","c1:wts=be","c1:ws=1","c1:s=","bg:-1:0:ws=1__w=can","bg:-1:0:ws=1__ws=1","bg:-1:0:ct=CMS__w=can","bg:-1:0:ct=CMS__ws=1","bg:0:1:w=can__w=be","bg:0:1:w=can__ws=1","bg:0:1:ws=1__w=be","bg:0:1:ws=1__ws=1"), features.get(6).toList());
        assertArrayMatch(Arrays.asList("4G=^be$","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","$STOP:CLOSEDCLASS","$STOP:NCW","$STOP:UDW","c-1:w=can","c-1:wts=can","c-1:ws=1","c-1:s=","c0:w=be","c0:wts=be","c0:ws=1","c0:s=","c1:w=condensed","c1:wts=condensed","c1:ws=1","c1:s=","bg:-1:0:w=can__w=be","bg:-1:0:w=can__ws=1","bg:-1:0:ws=1__w=be","bg:-1:0:ws=1__ws=1","bg:0:1:w=be__ws=1","bg:0:1:ws=1__ws=1"), features.get(7).toList());
        assertArrayMatch(Arrays.asList("4G=^con","3G=con","4G=cond","2G=on","3G=ond","4G=onde","1G=n","2G=nd","3G=nde","4G=nden","1G=d","2G=de","3G=den","4G=dens","1G=e","2G=en","3G=ens","4G=ense","1G=n","2G=ns","3G=nse","4G=nsed","1G=s","2G=se","3G=sed","4G=sed$","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","$STOP:UDW","c-1:w=be","c-1:wts=be","c-1:ws=1","c-1:s=","c0:w=condensed","c0:wts=condensed","c0:ws=1","c0:s=","c1:w=with","c1:wts=with","c1:ws=1","c1:s=","bg:-1:0:w=be__ws=1","bg:-1:0:ws=1__ws=1","bg:0:1:ws=1__ws=1"), features.get(8).toList());
        assertArrayMatch(Arrays.asList("4G=^wit","3G=wit","4G=with","2G=it","3G=ith","4G=ith$","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","ngram-=CM","$STOP:CLOSEDCLASS","$STOP:NCW","$STOP:UDW","c-1:w=condensed","c-1:wts=condensed","c-1:ws=1","c-1:s=","c0:w=with","c0:wts=with","c0:ws=1","c0:s=","c1:w=benzaldehydes","c1:wts=benzaldehyde","c1:ws=1","c1:s=s","c1:ct=CMS","bg:-1:0:ws=1__ws=1","bg:0:1:ws=1__ws=1","bg:0:1:ws=1__ct=CMS"), features.get(9).toList());
        assertArrayMatch(Arrays.asList("4G=^ben","3G=ben","4G=benz","2G=en","3G=enz","4G=enza","1G=n","2G=nz","3G=nza","4G=nzal","1G=z","2G=za","3G=zal","4G=zald","1G=a","2G=al","3G=ald","4G=alde","1G=l","2G=ld","3G=lde","4G=ldeh","1G=d","2G=de","3G=deh","4G=dehy","1G=e","2G=eh","3G=ehy","4G=ehyd","1G=h","2G=hy","3G=hyd","4G=hyde","1G=y","2G=yd","3G=yde","4G=ydes","1G=d","2G=de","3G=des","4G=des$","ngram+=CMS","ngram+=CMS","ngram+=CMS","ngram+=CMS","ngram+=CMS","ngram+=CMS","ngram+=CMS","ngram+=CMS","ngram+=CMS","c-1:w=with","c-1:wts=with","c-1:ws=1","c-1:s=","c0:w=benzaldehydes","c0:wts=benzaldehyde","c0:ws=1","c0:s=s","c0:ct=CMS","c1:w=.","c1:wts=.","c1:s=","bg:-1:0:ws=1__ws=1","bg:-1:0:ws=1__ct=CMS","bg:0:1:ws=1__w=.","bg:0:1:ct=CMS__w=."), features.get(10).toList());
        assertArrayMatch(Arrays.asList("c-1:w=benzaldehydes","c-1:wts=benzaldehyde","c-1:ws=1","c-1:s=s","c-1:ct=CMS","c0:w=.","c0:wts=.","c0:s=","bg:-1:0:ws=1__w=.","bg:-1:0:ct=CMS__w=."), features.get(11).toList());
        
        
    }

    private void assertArrayMatch(List<String> expected, List<String> actual) {
        assertEquals(expected, actual);
//        assertEquals(expected.size(), actual.size());
//        for (String s : expected) {
//            assertTrue("Contains "+s, actual.remove(s));
//        }
    }

}